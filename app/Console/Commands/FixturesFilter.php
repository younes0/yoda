<?php

namespace Yoda\Console\Commands;

use Illuminate\Console\Command;

class FixturesFilter extends Command
{
    protected $signature = 'yoda:fixtures'; 
    
    protected $description = 'Fixtures filtering';

    public function handle()
    {
        $iterator = new \FilesystemIterator(base_path().'/resources/fixtures/unfiltered');
        
        foreach ($iterator as $fileinfo) {
            $contents = file_get_contents($fileinfo->getPathname());
            file_put_contents($fileinfo->getPathname(), $this->filter($contents));
        }

        $this->info('done');
    }

    protected function filter($contents)
    {
        $removeKeys = [
            'contributors',
            'coordinates',
            'display_url',
            'extended_entities',
            'geo',
            'id_str',
            'in_reply_to_screen_name',
            'in_reply_to_status_id',
            'in_reply_to_status_id_str',
            'in_reply_to_user_id',
            'in_reply_to_user_id_str',
            'indices',
            'is_quote_status',
            'place',
            'possibly_sensitive',
            'possibly_sensitive_appealable',
            'source',
            'symbols',
            'truncated',
            'user_mentions',
        ];

        $array = json_decode($contents, true, 100);

       \YebArray::recursiveDelete($array, function($v, $k) use ($removeKeys) {
            return in_array($k, $removeKeys);
        });
     
        return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
