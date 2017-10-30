<?php

use Yoda\Models;

class ModelsTest extends \Codeception\TestCase\Test
{
    /**
     * @var \CodeGuy
     */
    protected $codeGuy;

    protected function _before() 
    {
        $this->codeGuy->migrate();
    }

    protected function _after() 
    {

    }

    public function testCreateUser()
    {
        $user = factory(Models\User::class)->create();

        // assert
        $this->codeGuy->seeRecord('users', [
            'email'    => $user->email,
            'is_admin' => false,
        ]);
    }
}
