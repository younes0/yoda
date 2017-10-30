<?php 

namespace Yoda\Curation;

use Yoda\Models\Collect;
use Illuminate\Console\Scheduling\Schedule;
use Artisan;

class Scheduler
{
    public function setup(Schedule $schedule)
    {
        // 5m: tweets fetch
        $schedule->command('curation:fetch')->everyFiveMinutes();

        // 15m: filter (+ scrape), classify & publish
        $schedule
            ->command('curation:filter')
            ->cron('*/15 * * * *')
            ->then(function() {
                Artisan::call('curation:classify');
                Artisan::call('curation:publish');
            });

        // // later: delayed publish
        // $schedule->command('curation:publish')->cron($cron);
        
        // // later: metrics/rate
        // $schedule->command('curation:metrics')->hourly();
        // $cron = sprintf('*/%d * * * *', config('yoda.curation.sourceAge'));
        // $schedule->command('curation:rate')->cron($cron);
    }
}
