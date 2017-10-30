<?php

namespace Yoda\Nlp\Console\Archives;

use Illuminate\Console\Command;
use Yoda\Nlp\Models\Document;
use Illuminate\Support\Facades\Redis;

class TextblobCommand extends Command 
{
    protected $signature = 'nlp:textblob'; 

    protected $description = 'Textblob NLP';

    public function handle()
    {
        $db    = \DB::connection('nlp');
        $redis = Redis::connection();

        $redis->set('train_set', $this->getArray($this->getTrainingDocs()));
        $redis->set('test_set', $this->getArray($this->getTestingDocs()));
       
        $this->info('done');
    }

    protected function getArray($documents)
    {  
        $output = [];
        foreach ($documents as $doc) {
            $output[] = [
                implode(' ', $doc->tokens),
                $doc->class,
            ];
        }

        return json_encode($output);
    }

    protected function getTrainingDocs()
    {
        return $this->getAllDocs()->filter(function($item) {
            return $item->id > (27205 + 5000);
            return !($item->id % 2);
        });
    }
    
    protected function getTestingDocs()
    {
        return $this->getAllDocs()->filter(function($item) {
            return $item->id < (27205 + 5000);
            // return !!($item->id % 2);
        });
    }

    protected function getAllDocs()
    {
        // return \Cache::remember('users', 0, function() {
            return Document::orderByRaw('random()')->get();
            // return Document::limit(100)->orderByRaw('random()')->get();
        // });
    }
}
