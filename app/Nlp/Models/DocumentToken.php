<?php

namespace Yoda\Nlp\Models;

use Yeb\Laravel\ExtendedModel;

class DocumentToken extends ExtendedModel 
{  
    public $connection = 'nlp';

    public $table = 'documents_tokens';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function token()
    {
        return $this->belongsTo(Document::class);
    }
}
