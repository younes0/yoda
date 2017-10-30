<?php

namespace Yoda\Nlp\Models;

use Yeb\Laravel\ExtendedModel;

class NlpClassed extends ExtendedModel 
{
    public $table = 'nlp_classed';

    protected $guarded = ['id'];

    public function model() 
    {
        return $this->morphTo();
    }
}
