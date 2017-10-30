<?php

namespace Yoda\Models;

use Illuminate\Database\Eloquent\Collection;
use Yeb\Laravel\ExtendedModel;
use Carbon\Carbon;
use Yoda\Libraries\ContentExtractor;
use Yoda\Libraries\Url;
use League\Url\Url as LeagueUrl;
use Yoda\Curation\LinksScraper;
use Yoda\Nlp\Models\ModelTrait as NlpTrait;
use Yoda\Nlp\Models\ModelInterface as NlpInterface;

class Link extends ExtendedModel implements NlpInterface
{
    use TaggableTrait;
    use NlpTrait;
    
    public $table = 'links';

    protected $guarded = ['id'];

    protected $casts = [
        'is_human_approved'   => 'boolean',
        'is_machine_approved' => 'boolean',
        'has_paywall'         => 'boolean',
        'is_nlpdoc_checked'   => 'boolean',
        'images_url'          => 'array',
    ];

    protected $dates = ['published_at', 'rated_at'];

    public function setRatingAttribute($value)
    {
        $this->attributes['rating']   = $value;
        $this->attributes['rated_at'] = Carbon::now();
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class, 'expanded_url', 'url');
    }

    public function metrics()
    {
        return $this->hasMany(LinkMetrics::class);
    }

    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function scopeRecent($query)
    {
        $date = Carbon::now()->subMinutes(config('yoda.curation.linkAge'));

        return $query->where('created_at', '>=', $date);
    }

    public function scopeApproved($query)
    {
        return $query
            ->whereRaw('is_human_approved IS NOT false')
            ->where(function($sub) {
               $sub
                   ->where('is_human_approved', true)
                   ->orWhere('is_machine_approved', true);
            });
    }

    public function scopeNotRejected($query)
    {
        return $query
            ->whereRaw('is_human_approved IS NOT false')
            ->whereRaw('is_machine_approved IS NOT false')
        ;
    }

    public function scopeHasNoPost($query)
    {
        return $query->doesntHave('post');
    }

// Non-Eloquent
// ---------------------------------------------------------------

    public function setHtmlAttribute($value)
    {
        $this->attributes['html'] = \YebString::toUTF8($value);
    }

    public function extractContent()
    {
        $this->html || $this->scrape();

        $content = (new ContentExtractor($this->html, $this->url))->getBest();
        $embed   = Url::getEmbed(null, $this->html);

        if (!$embed || !$content) return;

        try {
            $date = $embed->getPublishedTime();
            $publishedAt = $date ? Carbon::parse($date) : null;
        
        } catch (\Exception $e) {
            $publishedAt = null;
        }

        $this->update([       
            'host'         => LeagueUrl::createFromUrl($this->url)->getHost(),
            'content'      => \YebString::squeeze($content),
            'lang'         => \YebString::detectLang($content),
            'type'         => $embed->type, // link or files
            'title'        => trim($embed->title),
            'description'  => trim($embed->description),
            'images_url'   => array_pluck($embed->getImagesUrls(), 'value'),
            'published_at' => $publishedAt,
        ]);

        return $this;
    }

    public function updateMetrics()
    {
        $shares    = 0;
        $retweets  = 0;
        $favorites = 0;
        
        foreach ($this->tweets as $tweet) {
            $retweets  += $tweet->retweet_count;
            $favorites += $tweet->favorite_count;
            $shares    += Url::getLinkShares($tweet->url);
        }

        $this->metrics()->create(compact('shares', 'retweets', 'favorites'));

        return $this;
    }

    public function getLatestMetrics()
    {
        return $this->metrics->sortByDesc('created_at')->first();
    }

    public function approve($value)
    {
        $this->update(['is_human_approved' => $value]);

        if ($this->post) {
            if ($value) {
                $this->post->update(['is_ignored' => false]);
                $this->post->publish();
            
            } else {
                $this->post->unpublish();
            }
        }

        return $this;
    }

    protected function scrape()
    {
        $collection = (new Collection)->add($this);
        $scraper = new LinksScraper($collection);
        $scraper->boot();

        return $this;
    }

// NLP
// ---------------------------------------------------------------
    
    public function getNlpProps()
    {   
        $content = null;
        foreach (['title', 'description', 'content'] as $field) {
            $content.= $this->$field.PHP_EOL;
        }

        return [
            // 'classifier' => 'law-fr',
            'source'     => $this->url, 
            'content'    => !!trim($content) ? $content : null,
        ];
    }
}
