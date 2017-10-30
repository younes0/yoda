<?php

namespace Yoda\Console\Curation;

use Illuminate\Console\Command;
use Yoda\Models\Origin;
use Yoda\Curation\TweetsFetcher;

class FetchAndPopulate extends Command
{
    protected $signature = 'curation:fetch {originId?} {--mocked}'; 
    
    protected $description = 'Fetch Tweets & Populate Links';

    public function handle()
    {
       $origins = ($id = $this->argument('originId')) 
            ? Origin::find([$id]) 
            : Origin::all();

        foreach ($origins as $origin) {
            $fetcher = new TweetsFetcher($origin, $this->option('mocked'));
            $fetcher->boot();
            
            if ($collect = $fetcher->collect) {
                $collect->expandUrls()->populateLinks();
            }
        }

        $this->info('done');
    }
}
