<?php

namespace Yoda\Nlp\Libraries;

use NlpTools\Documents\DocumentInterface;
use NlpTools\FeatureFactories\FunctionFeatures;
use NlpTools\Analysis\Idf;
 
class TfIdfFeatureFactory extends FunctionFeatures
{
    protected $idf;
 
    public function __construct(Idf $idf, array $functions)
    {
        parent::__construct($functions);
        $this->modelFrequency();
        $this->idf = $idf;
    }
 
    public function getFeatureArray($class, DocumentInterface $doc)
    {
        $frequencies = parent::getFeatureArray($class, $doc);
        foreach ($frequencies as $term=>&$value) {
            $value = $value*$this->idf[$term];
        }
        return $frequencies;
    }
}
