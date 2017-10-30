<?php

namespace Yoda\Models;

use Yeb\Laravel\ExtendedModel;

class Scraped extends ExtendedModel 
{
    public $connection = 'nlp';
    
    public $table = 'scraped';

    protected $guarded = ['id'];
    
    protected $casts = [
        'json' => 'array',
    ];

    public function setHtmlAttribute($value)
    {
        $this->attributes['html'] = \YebString::toUTF8($value);
    }
}
