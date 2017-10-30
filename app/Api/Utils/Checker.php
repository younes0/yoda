<?php

namespace Yoda\Api\Utils;

class Checker
{
    static $services = ['twitter'];

    protected $tokens;

    public function __construct()
    {
        $this->tokens = \App::make('Tokens');
    }

    public function run()
    {
        return $this
            ->checkTokens()
            ->checkCalls();
    }

    protected function checkTokens()
    {
        foreach (static::$services as $service) {
            if ( !$this->tokens->get($service) ) {
                throw new \Exception('Token missing: '.$service);
            }
        }

        return $this;
    }

    protected function checkCalls()
    {
        Helper::getHomeTimeline([]);

        return $this;
    }
}