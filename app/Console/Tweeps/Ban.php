<?php

namespace Yoda\Console\Tweeps;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Yoda\Models\Host;
use Yoda\Models\Tweep;
use Yoda\Libraries\Utils;

class Ban extends Command
{
    protected $signature = 'tweeps:ban'; 
    
    protected $description = 'Tweeps Ban';

    public function handle()
    {
        $bannedHosts = Host::where('is_banned', true)->lists('id')->all();

        $usernames = \DB::table('tweets')
            ->select('user_name')
            ->distinct()
            ->leftJoin('links', 'links.url', '=', 'tweets.expanded_url')
            ->whereIn('links.host', $bannedHosts)
            ->lists('user_name');

        foreach (Tweep::whereIn('id', $usernames) as $tweep) {
            $tweep->update(['is_human_approved' => false]);
        }

        $this->info('done');
    }
}
