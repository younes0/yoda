<?php

namespace Yoda\Console\Commands;

use Illuminate\Console\Command;
use DB;

class Flush extends Command
{
    protected $signature = 'yoda:flush'; 
    
    protected $description = 'Yoda Flush';

    public function handle()
    {
        if (\App::environment() === 'production') return;

        $tables = [
            'collects',
            'links',
            'nlp_classed',
            'posts',
            'tagged',
        ];

        foreach ($tables as $table) {
            DB::statement('TRUNCATE TABLE '.$table.' CASCADE');
        }
    }
}
