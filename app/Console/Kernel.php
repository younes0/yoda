<?php 

namespace Yoda\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Yoda\Console\Commands;
use Yoda\Console\Corpus;
use Yoda\Console\Curation;
use Yoda\Console\Tweeps;
use Yoda\Nlp;
use Yoda\Echos;

class Kernel extends ConsoleKernel 
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\FixturesFilter::class,
        Commands\Flush::class,
        Curation\Classify::class,
        Curation\FetchAndPopulate::class,
        Curation\Filter::class,
        Curation\Metrics::class,
        Curation\Publish::class,
        Curation\Test::class,
        Nlp\Console\Archives\LdaCommand::class,
        Nlp\Console\Archives\TextblobCommand::class,
        Nlp\Console\NGramsCommand::class,
        Nlp\Console\PopulateCommand::class,
        Nlp\Console\TestCommand::class,
        Nlp\Console\TokenizeCommand::class,
        Tweeps\Ban::class,
        Tweeps\Discover::class,
        Tweeps\Filter::class,
        Tweeps\SyncList::class,
        Tweeps\Update::class,
    ];

    /**
     * Define the application's command schedule.
     * Add '* * * * * php /www/yoda/artisan schedule:run 1>> /dev/null 2>&1'
     * 
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // curation
        $curationScheduler = new \Yoda\Curation\Scheduler();
        $curationScheduler->setup($schedule);

          // backup daily
        // $schedule->call(function() {
        //     \App::make(\BackupManager\Manager::class)
        //         ->makeBackup()
        //         ->run('pgsql', 'local', 'backup.sql', 'gzip');  

        // })->daily();
    }  
}
