<?php

namespace Yoda\Nlp\Libraries;

use Illuminate\Database\Eloquent\Collection;
use Yoda\Nlp\Models\Document;
use Camspiers\StatisticalClassifier\{
    Classifier\ComplementNaiveBayes,
    Classifier\SVM,
    DataSource\DataArray,
    Model\Model,
    Model\CachedModel,
    Model\SVMModel
};

class Camspiers extends AbstractLibrary
{    
    public function classify($mixed)
    {
        $tokens = ($mixed instanceof Document) ? $mixed->getTokens() : $mixed;
        if (empty($tokens)) return $this->setNoClass();

        return [
            'class' => $this->classifier->classify(implode(' ', $tokens)),
            'score' => null,
        ];
    }

    protected function build()
    {
        $source = new DataArray;
        
        foreach ($this->getDocuments(true) as list($cat, $content)) {
            $source->addDocument($cat, $content);
        }

        $classifier = ($this->type === 'SVM')
            ? new SVM($source, new SVMModel, null, new CamspiersTokenizer)
            : new ComplementNaiveBayes($source, new Model, null, new CamspiersTokenizer);

        return $classifier;
    }
}
