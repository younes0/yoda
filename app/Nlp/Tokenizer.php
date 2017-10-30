<?php

namespace Yoda\Nlp;

use NlpTools\Utils\Normalizers\Normalizer;
use NlpTools\Utils\StopWords;
use NlpTools\Documents\TokensDocument;
use Yoda\Nlp\Transformers;

class Tokenizer
{
    static public function tokenize($str, $method)
    {
        return static::$method($str);
    }

    static public function classic($str)
    {
        $stopwords = array_merge(
            Transformers\FrenchStopwords::$stopwords, 
            Transformers\YodaStopwords::$stopwords
        );
        
        $transformers = [
            new StopWords($stopwords),
            new Transformers\FrenchStemmer(),
            new Transformers\MinLength(3),
        ];

        $str = preg_replace('~[^\p{L}]++~u', ' ', $str);
        $str = mb_strtolower($str, 'utf-8');
        $str = \YebString::removeAccents($str);

        $doc = new TokensDocument(array_filter(explode(' ', $str)));

        foreach ($transformers as $t) {
            $doc->applyTransformation($t);
        }

        // add bigrams
        $output = array_merge(
            $doc->getDocumentData(),
            Transformers\NGrams::extract($str)
        );

        return $output;
    }
}
