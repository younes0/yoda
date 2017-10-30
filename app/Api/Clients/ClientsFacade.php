<?php

namespace Yoda\Api\Clients;

use Illuminate\Support\Facades\Facade;

class ClientsFacade extends Facade 
{
    protected static function getFacadeAccessor() 
    { 
        return ClientsInterface::class;
    }
}
