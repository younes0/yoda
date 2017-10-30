<?php

namespace Yoda\Http\Middleware;

class VerifyCsrfToken extends \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
{
    /**
     * Routes excluded from crsf check     * 
     * @var array
     */
    protected $except = [

    ];
}
