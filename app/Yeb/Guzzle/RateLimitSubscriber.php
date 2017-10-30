<?php

namespace Yeb\Guzzle;

use GuzzleHttp\Collection;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\EmitterInterface;
use GuzzleHttp\Event\SubscriberInterface;
use Cache;

class RateLimitSubscriber implements SubscriberInterface
{
    /** @var Collection Configuration settings */
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = Collection::fromConfig($config, [
            'delay' => '1000',
        ]);
    }

    public function getEvents()
    {
        return [
            'before'   => ['onBefore'],
            'complete' => ['onComplete'],
        ];
    }

    public function onBefore(BeforeEvent $e)
    {
        $ms = $this->config['delay'] * 1000;

        if ($time = Cache::get('guzzleRateLimit.'.$this->getHost($e)) ) {
            $elapsed = microtime(true) - $time;

            if ($elapsed < $ms) {
                usleep($ms - $elapsed);
            }
        }
    }

    public function onComplete(CompleteEvent $e)
    {
        Cache::forever('guzzleRateLimit.'.$this->getHost($e), microtime(true));
    }

    protected function getHost($e)
    {
        return $e->getRequest()->getHost();
    }
}
