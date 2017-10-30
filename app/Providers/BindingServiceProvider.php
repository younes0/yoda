<?php

namespace Yoda\Providers;

use Yeb\Providers\BindingServiceProvider as ServiceProvider;
use Yoda\Api\Tokens;
use Yoda\Api\Clients;

class BindingServiceProvider extends ServiceProvider 
{
    public function register()
    {
        $this->app->bind('Tokens', function() {
            return ($this->app->environment() === 'testing')
                ? new Tokens\Cache 
                : new Tokens\Database;
        });

        $this->app->bind(Clients\ClientsInterface::class, Clients\Real::class);
        // Clients\ClientsBase::setBuildFixtures(true);
        // $this->app->bind(Clients\ClientsInterface::class, Clients\Mock::class);
    }
}
