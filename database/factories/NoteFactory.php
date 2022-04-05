<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Note;
use App\User;
use App\CurationGroup;
use Faker\Generator as Faker;

$factory->define(Note::class, function (Faker $faker) {
    $groups = CurationGroup::all();
    if ($groups->count() == 0) {
        $groups = CurationGroup::factory(1)->create();
    }
    return [
        'notable_type' => CurationGroup::class,
        'notable_id' => $groups->random()->id,
        'content' => $faker->paragraph,
        'created_by_id' => User::all()->random()->id
    ];
});
