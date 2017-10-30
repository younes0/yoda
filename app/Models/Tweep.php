<?php

namespace Yoda\Models;

use Yeb\Laravel\ExtendedModel;
use Yoda\DataProcessors\{TweetTransformer, TweepTransformer, TweepFilterer};
use Yoda\Libraries\Utils;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;

class Tweep extends ExtendedModel 
{
    public $table = 'tweeps';

    protected $dates = ['last_tweet_at', 'metrics_updated_at'];

    public static $unguarded = true;

    protected $casts = [
        'is_human_approved'   => 'boolean',
        'is_machine_approved' => 'boolean',
        'tweets_urls' => 'array',
    ];

    public function tweets()
    {
        return $this->hasMany(Tweet::class, 'user_name');
    }

    public function scopeNotRejected($query)
    {
        return $query
            ->whereRaw('is_human_approved IS NOT false')
            ->whereRaw('is_machine_approved IS NOT false')
        ;
    }

    public function getLinks()
    {
        $links = [];

        foreach ($this->tweets()->with('link')->get() as $tweet) {
            if ($tweet->link) {
                $links[] = $tweet->link;
            }
        }

        return collect($links);
    }

    public function updateMetrics()
    {
        try {
            $json = \ApiClients::twitter()->get('statuses/user_timeline.json', [ 'query' => [
                'screen_name' => $this->id,
                'count'       => 200,
            ]])->json();  

            $tweets = collect(Utils::transformData($json, new TweetTransformer));
            $count  = $tweets->count();
           
            if ( !$count) return $this->delete();

            // values
            $urls  = array_flatten(array_filter($tweets->pluck('urls')->all()));
            $langs = $tweets->pluck('lang')->reject(function ($item) {
                return $item === 'und';
            });

            $linksCount = count($urls);
            $langCount  = \YebArray::countValuesOf('fr', $langs->all());
            $interval   = $tweets->last()['published_at']->diffInDays($tweets->first()['published_at']);

            $this->update([
                'tweets_urls'           => $urls,
                'tweets_per_day'        => round($count / ($interval ?: 1), 2),
                'links_per_tweet'       => round($linksCount / $count, 2),
                'proper_lang_per_tweet' => round($langCount / $count, 2),
                'metrics_updated_at'    => Carbon::now(),
            ]);

            return $this;

        } catch (ClientException $e) {
            if (in_array($e->getResponse()->getStatusCode(), [401, 404])) {
                return $this->delete();
            }
        }
    }

    public function updateProperDomainPerLink()
    {
        $minLinks = 5;
        $proper   = 0;
        $links    = $this->getLinks();
        
        if ($links->count() < $minLinks) return;

        foreach ($links as $link) {
            if ($nlpClassed = $link->nlpClassed) {
                if ($nlpClassed->nlp_model === 'law-fr') {
                    $proper++;
                }
            }
        }

        return $this->update([
            'proper_domain_per_link' => $proper / $links->count()
        ]);
    }
}
