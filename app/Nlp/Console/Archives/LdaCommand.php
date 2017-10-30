<?php

namespace Yoda\Nlp\Console\Archives;

use Illuminate\Console\Command;
use Yoda\Nlp\Models\Document;
use NlpTools\FeatureFactories\DataAsFeatures;
use NlpTools\Documents\TokensDocument;
use NlpTools\Documents\TrainingSet;
use NlpTools\Models\Lda;

class LdaCommand extends Command 
{
    protected $signature = 'nlp:lda'; 

    protected $description = 'NLP LDA';

    public function handle()
    {
        $tset = new TrainingSet(); // training documents
        $docs = Document::limit('1000')->get();
        foreach ($docs as $doc) {
            $tset->addDocument($doc->class, new TokensDocument($doc->tokens));
        }

        $lda = new Lda(
            new DataAsFeatures(), // a feature factory to transform the document data
            5, // the number of topics we want
            1, // the dirichlet prior assumed for the per document topic distribution
            1  // the dirichlet prior assumed for the per word topic distribution
        );
         
        // run the sampler 50 times
        $lda->train($tset, 50);
         
        // synonymous to calling getPhi() as per Griffiths and Steyvers
        // it returns a mapping of words to probabilities for each topic
        // ex.:
        // Array(
        //   [0] => Array(
        //      [word1] => 0.0013...
        //      ....................
        //      [wordn] => 0.0001...
        //     ),
        //   [1] => Array(
        //      ....
        //     )
        // )
        print_r(
            // $lda->getPhi(10)
            // just the 10 largest probabilities
            $lda->getWordsPerTopicsProbabilities(10)
        );

        $this->info('done');
    }
}
