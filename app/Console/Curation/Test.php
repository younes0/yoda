<?php

namespace Yoda\Console\Curation;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Yoda\Models\Link;

class Test extends Command
{
    protected $signature = 'curation:test';

    protected $description = 'Test Curation';

    public function handle()
    {
        $query = Link::where('created_at', '>', Carbon::now());

        $this->exec('fetch');
        $this->info(with(clone $query)->count());
       
        $this->exec('filter');
        $this->info(with(clone $query)->approved()->count());

        $this->exec('classify');
        $this->info(with(clone $query)->isNlpClassed('law-fr')->hasNoPost()->count());

        $this->exec('publish');
        $this->info(with(clone $query)->has('post')->count());

        $this->info('done');
    }

    protected function exec($command)
    {
        $this->info($command.'...');
        \Artisan::call('curation:'.$command);
    }
}
