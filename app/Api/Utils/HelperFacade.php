<?php

namespace Yoda\Api\Utils;

use Illuminate\Support\Facades\Facade;

class HelperFacade extends Facade 
{
    protected static function getFacadeAccessor() 
    { 
        return Helper::class;
    }
}
