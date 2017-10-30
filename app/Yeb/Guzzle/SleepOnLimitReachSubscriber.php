<?php

namespace Yeb\Guzzle;

use GuzzleHttp\Event\EndEvent;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Message\Response;
use Carbon\Carbon;

class SleepOnLimitReachSubscriber implements SubscriberInterface
{
    protected $headers = [
        'reset'     => 'x-rate-limit-reset',
        'remaining' => 'x-rate-limit-remaining',
    ];

    public function __construct(array $headers = [])
    {
        $this->headers = array_merge($headers, $this->headers);
    }

    public function getEvents()
    {
        return [
            'end' => ['onEnd'],
        ];
    }

    public function onEnd(EndEvent $e)
    {
        $response = $e->getResponse();

        if ($e->getException() && $this->headers['reset']) {
            if ($response->getStatusCode() === 429) {
                $this->sleep($response);
                $e->intercept($e->getClient()->send($e->getRequest()));
            }
        
        } else if ($this->headers['remaining']) {
            if ($response->getHeader($this->headers['remaining']) === 0) {
                $this->sleep($response);
                $e->intercept($response);
            }
        }
    }

    protected function sleep(Response $response)
    {
        $resetAt = $response->getHeader($this->headers['reset']);
        $seconds = Carbon::createFromTimestamp($resetAt ?: time())->diffInSeconds();
        sleep($seconds);
    }
}
