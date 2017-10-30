<?php

namespace Yoda\Console\Tweeps;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Yoda\Models\Tweep;

class Update extends Command
{
    protected $signature = 'tweeps:update {action=metrics} {--all}'; 
    
    protected $description = 'Tweeps update metrics';

    public function handle()
    {
        $action = $this->argument('action');

        if ($this->option('all') || $action === 'domain') {
            $tweeps = Tweep::all();
        
        } else if ($action === 'metrics') {
            $tweeps = Tweep::notRejected()
                ->where('metrics_updated_at', null)
                ->orWhere('metrics_updated_at', '<', Carbon::now()->subMonths(3))
                ->get();
        }
        
        foreach ($tweeps as $tweep) {
            if ($action === 'metrics') {
                $tweep->updateMetrics();
            
            } else if ($action === 'domain') {
                $tweep->updateProperDomainPerLink();
            }

            $this->comment($tweep->id);
        }

        $this->info('done');
    }
}
