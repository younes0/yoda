<?php

namespace Yoda\Models;

use Yeb\Laravel\ExtendedModel;

class Tagged extends ExtendedModel 
{
    public $table = 'tagged';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function taggable()
    {
        return $this->morphTo();
    }

    public function getTag()
    {
        return Tag::where([
            'domain'   => $this->tag_domain,
            'codename' => $this->tag_codename,
        ])->first();
    }
}
