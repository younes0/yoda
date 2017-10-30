<?php

namespace Yoda\Nlp\Console;

use Illuminate\Console\Command;
use Yoda\Nlp\{Config, Tools};

class TestCommand extends Command 
{
    protected $signature = 'nlp:test {model}'; 

    protected $description = 'NLP Test';

    public function handle()
    {
        $model = $this->argument('model');

        $result = Tools::getTester($model)->boot();

        $this->info(sprintf(
            '%d correct; %d extra; %d documents for %s;', 
            $result['correct'], 
            $result['extra'],
            $result['total'],
            $model
        ));
    }
}
