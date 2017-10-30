<?php

use Yoda\Models;

class AuthTest extends \Codeception\TestCase\Test
{
    /**
     * @var \FunctionalGuy
     */
    protected $guy;

    protected function _before()
    {
        $this->guy->migrate();
        \Mail::pretend();
        $this->guy->withoutEvents();
    }

    protected function _after()
    {
    }

    public function testLogin()
    {
        $user = factory(Models\User::class)->create();

        // action
        $this->guy->setAndAssertUrl('/auth/login');

        $this->guy->submitForm('form', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->guy->seeCurrentUrlEquals('/home');
    }

    public function testRegister()
    {
        // setup
        $faker = Faker\Factory::create();

        $infos = [
            'email'     => $faker->freeEmail(),
            'firstname' => $faker->firstname,
            'lastname'  => $faker->lastname,
        ];

        // action
        $this->guy->setAndAssertUrl('/auth/register');

        // form submit in auth/register 
        $this->guy->submitForm('form', array_merge($infos, [
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]));

        // action/check authenticated
        $this->guy->seeAuthentication();
        $this->guy->seeCurrentUrlEquals('/home');
        $this->guy->seeInDatabase('users', array_only($infos, 'email'));
    }
}
