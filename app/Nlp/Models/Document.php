<?php

namespace Yoda\Nlp\Models;

use Yeb\Laravel\ExtendedModel;
use Yoda\Nlp\Config;
use Yoda\Nlp\Tokenizer;

class Document extends ExtendedModel 
{  
    public $connection = 'nlp';

    public $table = 'documents';

    protected $guarded = ['id'];

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    public function getTokens($replace = false)
    {
        if ($replace || !$this->tokens) {

            $this->update([
                'tokens' => Tokenizer::tokenize(
                    $this->content, 
                    config('yoda.nlp.tokenizers')[$this->domain]
                )
            ]);
        } 

        return $this->tokens;
    }

    public function documentTokens()
    {
        return $this->hasMany(DocumentToken::class);
    }

    public function setTokensAttribute($value)
    {
        $this->attributes['tokens'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }    

    public function getTokensAttribute($value)
    {
        return json_decode($value);
    }
}
