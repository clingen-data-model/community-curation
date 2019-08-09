<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User;
use App\Volunteer;
use Faker\Generator as Faker;

$factory->define(Volunteer::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    $user->assignRole('volunteer');
    return [
        'user_id' => $user->id,
        'volunteer_type_id' => $faker->shuffleArray([1,2]),
    ];
});
