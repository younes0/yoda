<?php

namespace Yoda\Console\Curation;

use Illuminate\Console\Command;
use Yoda\Models\Link;
use Carbon\Carbon;

class Metrics extends Command
{
    protected $signature = 'curation:metrics';

    protected $description = 'Update Link metrics';

    public function handle()
    {
        $date  = Carbon::now()->subMinutes(config('yoda.curation.sourceAge'));
        $links = Link::notRejected()->where('created_at', '>=', $date)->get();

        foreach ($links as $link) {

            // avoid same hour count duplicates
            $latest = $link->getLatestMetrics();
            if ($latest && ($latest->created_at->diffInHours() === 0)) {
                continue;
            }

            $link->updateMetrics();
        }

        $this->info('done');
    }
}
