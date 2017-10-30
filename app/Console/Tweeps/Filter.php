<?php

namespace Yoda\Console\Tweeps;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Yoda\Models\Tweep;
use Yoda\DataProcessors\TweepTransformer;
use Yoda\DataProcessors\TweepFilterer;
use Yoda\Libraries\Utils;

class Filter extends Command
{
    protected $signature = 'tweeps:filter {action=model} {--all}'; 
    
    protected $description = 'Tweeps filter model|raw';

    public function handle()
    {
        $action = $this->argument('action');
        $this->filterer = new TweepFilterer;

        if ($action === 'model') {
            $this->filterModel();

        } else if ($action == 'raw') {
            $this->filterRaw();
        }

        $this->info('done');
    }

    protected function filterModel()
    {
        $tweeps = $this->option('all')
            ? Tweep::all()
            : Tweep::whereRaw('metrics_updated_at IS NOT null')
                ->where('is_machine_approved', null)
                ->get();

        foreach ($tweeps as $tweep) {
            $approved = !$this->filterer->filterMetrics($tweep) && !$this->filterer->filterUrls($tweep);

            $tweep->update(['is_machine_approved' => $approved]);

            $this->comment($tweep->id);
        }
    }

    protected function filterRaw()
    {
        $tweeps = Tweep::all();

        foreach ($tweeps->chunk(100) as $chunk) {

            $names = $chunk->pluck('id')->all();

            $json = \ApiClients::twitter()->get('users/lookup.json', [ 'query' => [
                'screen_name' => implode(',', $names),
            ]])->json();  

            $data = Utils::transformData($json, new TweepTransformer);

            foreach ($chunk as $tweep) {
               
                $raw = collect($data)->where('screen_name', $tweep->id)->first();
                
                if ( !$raw || !$this->filterer->filterRaw($raw, false)) {
                    $this->comment($tweep->id);
                    $tweep->delete();   
                }
            }
        }
    }
}
