<?php

namespace Yoda\Nlp\Libraries;

use Yoda\Nlp\{
    Tokenizer,
    Models\Document,
    Tools
};
use NlpTools\{
    Analysis\Idf,
    Classifiers\FeatureBasedLinearClassifier,
    Classifiers\MultinomialNBClassifier,
    Documents\DocumentInterface,
    Documents\TokensDocument,
    Documents\TrainingSet,
    FeatureFactories\DataAsFeatures,
    FeatureFactories\FunctionFeatures,
    Models\FeatureBasedNB,
    Models\Maxent,
    Optimizers\ExternalMaxentOptimizer,
    Optimizers\MaxentGradientDescent
};
use SuperClosure\{
    SerializableClosure,
    Serializer,
    Analyzer\TokenAnalyzer
};

class Nlptools extends AbstractLibrary
{    
    const TDIDF = true;

    protected $serializer;

    public function __construct($type)
    {
        $this->type = $type;
        $this->seriallizer = new Serializer(new TokenAnalyzer);
    }

    public function classify($mixed)
    {
        if ($mixed instanceof Document) {
            $tokens = $mixed->getTokens();
            $doc    = (!$tokens || empty($tokens)) ? null : new TokensDocument($tokens);
        
        } else {
            $doc = $this->getTokensDoc($mixed);
        }

        if ( !$doc) return $this->setNoClass();

        $class = $this->classifier->classify($this->config->classes, $doc);

        return [
            'class' => $class,
            'score' => ($this->type === 'MultinomialNB')
                ? $this->classifier->getScore($class, $doc)
                : $this->classifier->getVote($class, $doc)
        ];
    }

    protected function build()
    {
        $tset = new TrainingSet(); // training documents

        foreach ($this->getDocuments() as list($cat, $string)) {
            $doc = $this->getTokensDoc($string);
            $doc && $tset->addDocument($cat, $doc);
        }

        if ($this->type === 'MultinomialNB') {

            $serializer = new Serializer(new TokenAnalyzer);

            $ff = !self::TDIDF
                ? new DataAsFeatures()
                : new TfIdfFeatureFactory(new Idf($tset), [
                    $this->serializeClosure(function($class, $doc) {
                        return $doc->getDocumentData();
                    })
                ]);

            $model = new FeatureBasedNB(); // train a Naive Bayes model
            $model->train($ff, $tset);

            $classifier = new MultinomialNBClassifier($ff, $model);

        } else if ($this->type === 'MaximumEntropy') {

            $featureFunction = function ($class, $doc) {
                return array_map(function($token) use ($class) {
                    return "$class ^ $token";
                }, $doc->getDocumentData());
            };

            // TDIDF: not working
            // $ff =  new TfIdfFeatureFactory(new Idf($tset), $featureFunction);
            $ff = new FunctionFeatures($this->serializeClosure($featureFunction));

            $model = new Maxent([]);

            $model->train($ff, $tset, new ExternalMaxentOptimizer(
                base_path().'/resources/other/gradient-descent.go'
            ));

            // caching: stoquer résultats en json (éviter serialize)
            $classifier = new FeatureBasedLinearClassifier($ff, $model);
        }

        return $classifier;
    }

    protected function getTokensDoc($string)
    {
        return new TokensDocument(Tokenizer::tokenize($string, $this->config->tokenizer));
    }

    protected function serializeClosure(callable $closure)
    {
        return new SerializableClosure($closure, $this->serializer);
    }
}
