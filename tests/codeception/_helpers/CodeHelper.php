<?php

namespace Codeception\Module;

// here you can define custom functions for TestGuy 

class CodeHelper extends \Codeception\Module
{
    protected $migrated = false;

    public function migrate()
    {
        if ($this->migrated or \Schema::hasTable('migrations')) {
            return;
        }

        \Artisan::call('migrate:install');
        \Artisan::call('migrate');
        $this->migrated = true;
    }

    public function withoutEvents()
    {
        $mock = \Mockery::mock('Illuminate\Contracts\Events\Dispatcher');

        $mock->shouldReceive('fire');
        $mock->shouldReceive('listen');

        $this->getModule('Laravel5')->getApplication()->instance('events', $mock);

        return $this;
    }

    public function withoutMiddleware()
    {
        $this->getModule('Laravel5')->getApplication()->instance('middleware.disable', true);

        return $this;
    }

    public function setAndAssertUrl($url)
    {
        $mod = $this->getModule('Laravel5');

        $mod->amOnPage($url);
        $mod->seeCurrentUrlEquals($url);
        $mod->seeResponseCodeIs(200);

        return $this;
    }

    public function logAdmin()
    {
        $user = factory(\Yskel\Models\User::class, 'admin')->create();
        $this->getModule('Laravel5')->amLoggedAs($user);

        return $user;
    }

}
