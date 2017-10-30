<?php

namespace Yoda\Providers;

use Redirect, Request, Session;
use Yeb\Providers\AppServiceProvider as ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider 
{
    /**
     * Boot
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        /* ... */
    }

    /**
     * Register
     *
     * @return void
     */
    public function register() 
    {
        parent::register();

        Carbon::setLocale('fr');
    }
}
