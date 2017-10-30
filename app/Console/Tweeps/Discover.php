<?php

namespace Yoda\Console\Tweeps;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Yoda\Models\Tweep;
use Yoda\DataProcessors\TweepTransformer;
use Yoda\DataProcessors\TweepFilterer;
use Yoda\Libraries\Utils;

class Discover extends Command
{
    protected $signature = 'tweeps:discover {tweepId?} {--max=200} {--complete}'; 
    
    protected $description = 'Tweeps discover';

    public function handle()
    {
        $filterer = new TweepFilterer; 
        $tweeps   = ($id = $this->argument('tweepId'))
            ? Tweep::find([$id])
            : Tweep::notRejected()->orderByRaw('random()')->get();
       
        $count = 0;

        // fetch tweep's followers
        foreach ($tweeps as $tweep) {
            $cursor = -1;

            while ($cursor) {
                $results = $this->apiCall($tweep, $cursor);
                $cursor  = $results['next'];

                foreach ($results['users'] as $follower) { 
                    if ($count > $this->option('max')) break 3;
                    if ($filterer->filterRaw($follower)) continue;

                    Tweep::create([
                        'id'          => $follower['screen_name'],
                        'description' => $follower['description'],
                    ]);

                    $count++;
                    
                    $this->comment($follower['screen_name']);
                }
            }
        }

        if ($this->option('complete')) {
            foreach (['update', 'filter', 'list'] as $command) {
                \Artisan::call('tweeps:'.$command);
            }
        }

        $this->info('done');
    }

    protected function apiCall(Tweep $tweep, $cursor = -1)
    {
        $json = \ApiClients::twitter()->get('followers/list.json', [ 'query' => [
            'screen_name' => $tweep->id,
            'count'       => 200,
            'cursor'      => $cursor,
        ]])->json();  

        return [
            'users' => Utils::transformData($json['users'], new TweepTransformer),
            'next'  => $json['next_cursor'],
        ];
    }
}
