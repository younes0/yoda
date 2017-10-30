<?php

use Yoda\Models;

class FrontendTest extends \Codeception\TestCase\Test
{
    /**
     * @var \FunctionalGuy
     */
    protected $guy;

    /**
     * Logged User
     * 
     * @var Laupad\Models\User
     */
    protected $user;

    protected function _before()
    {
        $this->guy->migrate();
        \Mail::pretend();
        $this->guy->withoutEvents();

        // log user
        $this->user = factory(Models\User::class)->create();
        $this->guy->amLoggedAs($this->user);
    }

    protected function _after()
    {
    }
    
    public function testUsersIndexAndDatatable()
    {
        $this->guy->setAndAssertUrl('/users');

        $this->guy->sendAjaxGetRequest('/users/datatable');
    }
}
