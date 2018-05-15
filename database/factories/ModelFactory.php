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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->email,
        'password' => app('hash')->make('secret'),
    ];
});

$factory->define(App\Todo::class, function (Faker\Generator $faker) {
    return [
        'user_id' => App\User::all()->random()->id,
        'body' => $faker->sentence(10),
        'complete' => rand(0,1),
        'priority' => rand(1,5)
    ];
});
