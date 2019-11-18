<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Aptitude;
use App\Attestation;
use Faker\Generator as Faker;

$factory->define(Attestation::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'aptitude_id' => Aptitude::all()->random()->id,
        'assignment_id' => null,
        'signed_at' => null
    ];
});
