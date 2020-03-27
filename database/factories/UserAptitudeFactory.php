<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Aptitude;
use App\User;
use App\UserAptitude;
use Faker\Generator as Faker;

$factory->define(UserAptitude::class, function (Faker $faker) {
    $aptitude = Aptitude::all()->random();
    return [
        'user_id' => factory(User::class)->create([]),
        'aptitude_id' => ($aptitude) ? $aptitude->id : factory(Aptitude::class)->create([]),
        'assignment_id' => null,
        'trained_at' => null,
        'granted_at' => null,
    ];
});
