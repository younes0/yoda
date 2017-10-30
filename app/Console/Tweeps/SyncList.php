<?php

namespace Yoda\Console\Tweeps;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Yoda\Models\Origin;
use Yoda\Models\Tweep;

class SyncList extends Command
{
    protected $signature = 'tweeps:list {originId?}'; 
    
    protected $description = 'Sync/Create List';

    public function handle()
    {
        if ( !$this->confirm('Confirm')) return;

        $client = \ApiClients::twitter();

        // check list if specified
        if ($id = $this->argument('originId')) {
            $origin = Origin::findOrFail($id);
            
            $list = $client->get('lists/show.json', [ 'query' => [
                'list_id'  => $origin->list_id,
                'owner_id' => $origin->account_id,
            ]])->json();  

        // create list
        } else {
            $list = $client->post('lists/create.json', [ 'query' => [
                'name' => Carbon::now()->toDateTimeString(),
                'mode' => 'private',
            ]])->json();  

            // create origin
            $origin = Origin::create([
                'type'       => 'list',
                'account_id' => config('yoda.curation.twitterAccount'),
                'list_id'    => $list['id'],
            ]);

            $this->info('new origin: #'.$origin->id);
        }
        
        $local  = Tweep::notRejected()->lists('id');
        $remote = collect();

        // TODO: enlever de local les remote qui n'existent plus
        while ($remote->count() !== $local->count()) {
            
            $results = $client->get('lists/members.json', [ 'query' => [
                'list_id' => $list['id'],
                'count'   => 5000,
            ]])->json();  

            $remote = collect($results['users'])->pluck('screen_name');

            // sync members
            $actions   = [
                'create'  => $local->diff($remote),
                'destroy' => $remote->diff($local),
            ];

            foreach ($actions as $action => $values) {
                $path   = sprintf('lists/members/%s_all.json', $action);
                $chunks = $values->chunk(100);

                foreach ($chunks as $chunk) {
                    $client->post($path, [ 'query' => [
                        'list_id'     => $list['id'],
                        'screen_name' => implode(',', $chunk->all()),
                    ]]);
                }
            }
        }

        $this->info('done, synced list #'.$list['id']);
    }
}
