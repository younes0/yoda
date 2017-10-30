<?php

namespace Yoda\Api\Clients;

use Yeb\Guzzle\Mocking;

class Mock extends Real implements ClientsInterface
{
    protected function buildClient(Array $config = [])
    {
        return Mocking::getMockerClient($config);
    }
}
