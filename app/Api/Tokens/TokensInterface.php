<?php

namespace Yoda\Api\Tokens;

interface TokensInterface
{
    public function get($name);
    
    public function set($name, $value);
}
