<?php

namespace Yoda\Console\Curation;

use Illuminate\Console\Command;
use Yoda\DataProcessors\LinkFilterer;
use Yoda\Curation\LinksScraper;
use Yoda\Curation\Tools;
use Yoda\Models\Link;
use Carbon\Carbon;

class Filter extends Command
{
    protected $signature = 'curation:filter';

    protected $description = 'Scrape & Filter links';

    public function handle()
    {
        $links = Link::recent()
            ->notRejected()
            ->where('is_machine_approved', null)
            ->get();

        // scrape html
        $scraper = new LinksScraper($links);
        $scraper->boot();

        // prevent ddos-block 
        sleep(10);
        
        // extract & filter content
        $filterer = new LinkFilterer;

        foreach ($links as $link) {
            $link->extractContent();
            $approved = !$filterer->filterScraped($link);
            $link->update(['is_machine_approved' => $approved]);
        }

        // remove duplicates
        Tools::deleteDuplicatedLinks();

        $this->info('done');
    }
}
