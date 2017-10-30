<?php

namespace Yoda\Nlp\Models;

use Yeb\Laravel\ExtendedModel;

class NGram extends ExtendedModel 
{  
    public $connection = 'nlp';

    public $table = 'ngrams';

    protected $guarded = [];

    public $timestamps = false;
}
