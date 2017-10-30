<?php

namespace Yoda\Models;

use Yeb\Laravel\ExtendedModel;

class LinkMetrics extends ExtendedModel 
{
    public $table = 'links_metrics';

    protected $guarded = ['id'];

    public function links()
    {
        return $this->belongsTo(Link::class, 'link_id');
    }
}
