<?php

namespace Yoda\Nlp\Transformers;

use NlpTools\Stemmers\Stemmer;

class FrenchStemmer extends Stemmer
{
    public function stem($word) 
    {
        return (in_array($word, static::$excluded)) 
            ? $word
            : \stemmer_stem_word($word, 'french', 'UTF_8');
    }

    static public $excluded = [
        'logement',
        'concurrence',
        'domaine',
    ];
}
