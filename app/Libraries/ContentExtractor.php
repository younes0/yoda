<?php

namespace Yoda\Libraries;

use Readability\Readability;
use Illuminate\Support\Facades\Redis;

class ContentExtractor
{   
    protected $url;

    protected $html;

    public function __construct($html, $url = null)
    {
        $this->url  = $url;
        $this->html = $html;
    }

    public function getBest($minLength = 1000)
    {
        $values = [];

        foreach (['readability', 'newspaper', 'goose'] as $method) {
            $result = $this->$method();
            $values[$method] = $result;

            if (strlen($result) >= $minLength) return $result;
        }

        return $values['readability'];
    }

    public function readability()
    {
        try {
            $readability = new Readability($this->html);

            if ($readability->init()) {
                return $readability->getContent()->textContent;   
            }
            
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public function newspaper()
    {
        Redis::connection()->set('scraped', $this->html);

        $command = sprintf('python3.5 %s/resources/python/extract-newspaper.py', base_path());

        return json_decode(exec($command));
    }

    public function goose()
    {
        Redis::connection()->set('scraped', $this->html);

        $command = sprintf('python2 %s/resources/python/extract-goose.py', base_path());

        return json_decode(exec($command));
    }

    public function aylien($sendHtml = false)
    {
        $query = $sendHtml
            ? ['html' => $this->html ]
            : ['url'  => $this->url ];

        $values = \ApiClients::aylien()->get('extract', [ 
            'query' => $query,
        ])->json();

        return (is_array($values) && isset($values['article'])) 
            ? $values['article'] 
            : null;
    }
}
