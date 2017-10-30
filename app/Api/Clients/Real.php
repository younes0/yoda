<?php

namespace Yoda\Api\Clients;

use Yeb\Guzzle\RateLimitSubscriber;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Yoda\Api\Tokens\TokensInterface as Tokens;
use Yeb\Guzzle\SleepOnLimitReachSubscriber;

class Real extends AbstractClients implements ClientsInterface
{
    protected $tokens;
    
    public function __construct()
    {
        $this->tokens = \App::make('Tokens');
    }

    public function jediwp()
    {
        $useOauth = !env('JEDIWP_BASICAUTH');

        $defaults = $useOauth
            ? ['auth'    => 'oauth']
            : ['headers' => ['Authorization' => 'Basic '.base64_encode(env('JEDIWP_BASICAUTH')) ]];
            
        $client = $this->buildClient([
            'base_url' => env('JEDIWP_URL').'/wp-json/',
            'defaults' => $defaults,
        ]);

        // oauth
        if ($useOauth) {
            $tokens = $this->tokens->get('jediwp');

            $client->getEmitter()->attach(new Oauth1([
                'consumer_key'    => env('JEDIWP_KEY'),
                'consumer_secret' => env('JEDIWP_SECRET'),
                'token'           => $tokens['identifier'],
                'token_secret'    => $tokens['secret'],
            ]));
        }

        return $client;
    }

    public function twitter($accountId = null)
    {
        $accountId = $accountId ?: config('yoda.curation.twitterAccount');

        $client = $this->buildClient([
            'base_url' => 'https://api.twitter.com/1.1/',
            'defaults' => ['auth' => 'oauth'],
        ]);

        // attach oauth1 + rate limit
        $tokens  = $this->tokens->get('twitter:'.$accountId);
        $emitter = $client->getEmitter();

        $emitter->attach(new Oauth1([
            'consumer_key'    => env('TWITTER_KEY'),
            'consumer_secret' => env('TWITTER_SECRET'),
            'token'           => $tokens['identifier'],
            'token_secret'    => $tokens['secret'],
        ]));

        $emitter->attach(new SleepOnLimitReachSubscriber);

        return $client;
    }

    public function aylien()
    {
        $client = $this->buildClient([
            'base_url' => 'https://api.aylien.com/api/v1/',
            'defaults' => [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-AYLIEN-TextAPI-Application-ID'  => env('AYLIEN_APP'),
                    'X-AYLIEN-TextAPI-Application-Key' => env('AYLIEN_KEY'),
                ]
            ],
        ]);

        // rate limit
        $emitter = $client->getEmitter();
        $emitter->attach(new SleepOnLimitReachSubscriber([
            'remaining' => 'X-RateLimit-Remaining',
            'reset'     => 'X-RateLimit-Reset',
        ]));
        $emitter->attach(new RateLimitSubscriber([
            'delay' => 1000,
        ]));

        return $client;
    }

    public function monkeylearn()
    {
        $client = $this->buildClient([
            'base_url' => 'https://api.monkeylearn.com/v2/',
            'defaults' => [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'token '.env('MONKEYLEARN_TOKEN'),
                ]
            ],
        ]);  

        $client->getEmitter()->attach(new SleepOnLimitReachSubscriber([
            'remaining' => 'X-Query-Limit-Remaining',
            'reset'     => null,
        ])); 

        return $client;
    }
}
