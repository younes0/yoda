<?php

namespace Yoda\Models;

use Yeb\Laravel\ExtendedModel;
use Yoda\Libraries\Url;
use Yoda\DataProcessors\LinkFilterer;

class Tweet extends ExtendedModel 
{
    public $table = 'tweets';

    protected $dates = ['published_at'];

    protected $casts = [
        'is_retweet' => 'boolean',
        'hashtags'   => 'array',
    ];

    protected $guarded = ['id'];

    public function collect()
    {
        return $this->belongsTo(Collect::class, 'collect_id');
    }

    public function link()
    {
        return $this->belongsTo(Link::class, 'expanded_url', 'url');
    }

    public function tweep()
    {
        return $this->belongsTo(Tweep::class, 'id', 'user_name');
    }

    static public function createFromRaw(Collect $collect, Array $raw)
    {
        $values = [
            'collect_id'   => $collect->id,
            'image_url'    => isset($raw['images'][0]) ? $raw['images'][0] : null,
            'published_at' => $raw['published_at'],
            'url'          => $raw['urls'][0],
        ];

        return static::create(array_merge($values, array_except($raw, [
            'urls', 'images', 'published_at'
        ])));
    }

    public function expandUrl()
    {
        return $this->update([
            'expanded_url' => Url::expandAndClean($this->url),
        ]);
    }

    public function populateLink()
    {
        $expanded = $this->expanded_url;
        $filterer = new LinkFilterer;

        if ( $expanded and !$filterer->filterRaw($expanded) ) {
            return Link::firstOrCreate(['url' => $expanded]);
        }
    }
}
