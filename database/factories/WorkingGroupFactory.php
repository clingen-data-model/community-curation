<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\WorkingGroup;
use Faker\Generator as Faker;

$factory->define(WorkingGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});
