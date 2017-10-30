<?php

namespace Yoda\Console\Curation;

use Illuminate\Console\Command;
use Yoda\Models\Link;
use Yoda\Curation\LinksClassifier;

class Classify extends Command
{
    protected $signature = 'curation:classify'; 

    protected $description = 'Links Classify';

    public function handle()
    {
        $links = Link::recent()
            ->approved()
            ->notNlpClassed()
            ->get();
        
        $classifier = new LinksClassifier($links);     
        $classifier->boot();

        $this->info('done');
    }
}
