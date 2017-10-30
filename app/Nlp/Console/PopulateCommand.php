<?php

namespace Yoda\Nlp\Console;

use Illuminate\Console\Command;
use Yoda\Nlp\Models\Document;
use Yoda\Models\Link;

class PopulateCommand extends Command 
{
    protected $signature = 'nlp:populate'; 

    protected $description = 'NLP Populate Documents';

    public function handle()
    {
        foreach (Link::all() as $link) {
            $link->createNlpDoc($link->url);
        }

        $this->info('done');
    }
}
