<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use App\TrainingSession;
use App\CurationActivity;
use Faker\Generator as Faker;

$factory->define(TrainingSession::class, function (Faker $faker) {
    $datetime = new Carbon($faker->dateTimeBetween('-1 year', '+1 year'));
    return [
        'topic_type' => CurationActivity::class,
        'topic_id' => CurationActivity::select('id')->get()->random()->id,
        'url' => $faker->url,
        'starts_at' => $datetime,
        'ends_at' => $datetime->addHours(1),
        'notes' => $faker->paragraph(1),
        'invite_message' => $faker->paragraph(2)
    ];
});

$factory->state(TrainingSession::class, 'future', function ($faker) {
    $datetime = new Carbon($faker->dateTimeBetween('now', '2021-01-01'));
    return [
        'starts_at' => $datetime,
        'ends_at' => $datetime->addHour()
    ];
});

$factory->state(TrainingSession::class, 'past', function ($faker) {
    $datetime = new Carbon($faker->dateTimeBetween('-1 year', 'now'));
    return [
        'starts_at' => $datetime,
        'ends_at' => $datetime->addHour()
    ];
});
