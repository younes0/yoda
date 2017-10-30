<?php

namespace Yoda\Api\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Retry\RetrySubscriber;
use GuzzleHttp\Subscriber\Log\LogSubscriber;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Yeb\Guzzle\Mocking;

abstract class AbstractClients
{  
    public function basic(Array $config = [])
    {   
        $config = $config ?: [
            'defaults' => [
                'verify' => false,
                'headers' => [
                    'User-Agent' => \YebUtils::$userAgents[0],
                ],
                'allow_redirects' => [
                    'max'     => 20,
                    'referer' => true,
                ],
            ],
        ];

        return $this->buildClient($config);
    }
    
    protected function buildClient(Array $config = [])
    {
        $client = new Client($config);

        // retry
        $client->getEmitter()->attach(new RetrySubscriber([ 
            'filter' => RetrySubscriber::createStatusFilter(),
        ]));

        // fixtures
        if (static::$buildFixtures) { 
            $service = debug_backtrace()[1]['function'];
            $client  = Mocking::addFixturesBuilder($client, $service);
        }

        // log
        // if (env('GUZZLE_LOG', false)) {
        //     $logFile = storage_path('logs/guzzle.log');
        //     $monolog = new Logger('guzzle');
        //     $monolog->pushHandler(new StreamHandler($logFile), Logger::INFO);

        //     $client->getEmitter()->attach(new LogSubscriber($monolog));
        // }

        return $client;
    }

    static public $buildFixtures = false;

    static public function setBuildFixtures($value)
    {
        static::$buildFixtures = $value;
    }
}
