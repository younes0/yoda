<?php

namespace Yoda\Models;

use Yeb\Laravel\ExtendedModel;
use Yoda\Libraries\Url;

class Collect extends ExtendedModel 
{
    public $table = 'collects';

    protected $guarded = ['id'];

    protected $casts = [
        'has_links_populated' => 'boolean',
        'exception'           => 'array',
    ];

    public function origin() 
    {
        return $this->belongsTo(Origin::class, 'origin_id');
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class, 'collect_id');
    }

    public function expandUrls()
    {
        $tweets = $this->tweets;

        // get expanded_url from expanded tweets with same urls 
        foreach ($tweets as $i => $tweet) {
            $similar = Tweet::where('url', $tweet->url)
                ->whereNotNull('expanded_url')
                ->first();

            if ($similar) {
                $tweet->update(['expanded_url' => $similar->expanded_url]);
                $tweets->forget($i);
            }
        }

        $results = Url::expandAndCleanMultiple($tweets->lists('url')->all());

        foreach ($results as $source => $expanded) {
            $tweet = Tweet::where('url', $source)->first();

            if ( !$expanded) {
                $tweet->delete();
            
            } else {
                $tweet->update(['expanded_url' => $expanded]);
            }
        }

        return $this;
    }

    public function populateLinks()
    {
        if ( !$this->exists || $this->has_links_populated) {
            return null;
        }

        $this->load('tweets');

        foreach ($this->tweets as $tweet) {
            $tweet->populateLink();
        }

        $this->update(['has_links_populated' => true]);

        return $this;
    }
}
