<?php

namespace Yoda\Nlp\Transformers;

use NlpTools\Stemmers\Stemmer;

class Ngrams
{
    static public function extract($string, $database = false)
    {
        $output = [];

        if ($database) {
            static::$ngrams = \Cache::remember('ngrams', 60, function() {
                return \DB::connection('nlp')
                    ->table('ngrams')
                    ->where('count', '>', 30)
                    ->lists('id');
            });
        }

        foreach (static::$ngrams as $ngram) {
            $matches = [];
            preg_match_all('/'.preg_quote($ngram, '/').'/', $string, $matches);
            $output = array_merge($output, $matches[0]);
        }

        return $output;
    }

    static protected $ngrams = [
        // ... 
    ];
}
