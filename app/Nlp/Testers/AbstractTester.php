<?php

namespace Yoda\Nlp\Testers;

use Yoda\Nlp\Config;
use Yoda\Nlp\Tools;

abstract class AbstractTester 
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function boot()
    {
        \DB::connection('nlp')
            ->table('documents')
            ->whereIn('domain', $this->config->domains)
            ->update(['classified_as' => null]);

        $lib = Tools::getLibrary($this->config->method);
        $lib->setup($this->config, $this->getTrainingDocs());

        $testingDocs = $this->getTestingDocs();

        $i = 0;
        $correct = 0;

        foreach ($testingDocs as $doc) {
            $result  = $lib->classify($doc);
            $correct+= intval($result['class'] === $doc->class);

            // remove temporary values (class etc)
            $doc->setRawAttributes($doc->getOriginal());

            $doc->update([
                'classified_as'    => $result['class'],
                'classified_score' => $result['score'],
            ]);

            echo $i.PHP_EOL;
            $i++;
        }

        return [
            'correct' => $correct, 
            'total'   => $testingDocs->count(),
            'extra'   => $this->getExtra(),
        ];
    }

    abstract protected function getTrainingDocs();

    abstract protected function getTestingDocs();

    protected function getExtra()
    {
        return null;
    }
}
