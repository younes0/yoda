<?php

namespace Yoda\Api\Clients;

interface ClientsInterface
{
    public function basic(Array $config = []);

    public function jediwp();

    public function twitter($accountId = null);

    public function aylien();

    public function monkeylearn();
}
