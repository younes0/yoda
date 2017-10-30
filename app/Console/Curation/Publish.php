<?php

namespace Yoda\Console\Curation;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Yoda\Models\Link;
use Yoda\Curation\Publisher;

class Publish extends Command
{
    protected $signature = 'curation:publish';

    protected $description = 'Create Posts and Publish';

    public function handle()
    {
        $links = Link::recent()
            ->isNlpClassed('law-fr') // approved
            ->hasNoPost()
            ->get();

        $publisher = new Publisher($links);
        $publisher->boot();

        $this->info('done');
    }
}
