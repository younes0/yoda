<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Yoda\Models\User::class, function($faker) {
    return [
        'email'          => $faker->email,
        'password'       => \Hash::make('password'),
        'remember_token' => str_random(10),
        'firstname'      => $faker->firstname,
        'lastname'       => $faker->lastname,
    ];
});

$factory->defineAs(Yoda\Models\User::class, 'admin', function($faker) use ($factory) {
    $user = $factory->raw(Yoda\Models\User::class);
   
    return array_merge($user, ['is_admin' => true]);
});
