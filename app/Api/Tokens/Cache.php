<?php

namespace Yoda\Api\Tokens;

class Cache implements TokensInterface
{
    public function get($name)
    {
        return \Cache::tags('tokens')->get($name);
    }

    public function set($name, $value)
    {
        return \Cache::tags('tokens')->forever($name, $value);
    }
}
