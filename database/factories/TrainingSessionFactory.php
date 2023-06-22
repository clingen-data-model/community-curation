<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CurationActivity;
use App\TrainingSession;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(TrainingSession::class, function (Faker $faker) {
    $fakeDateTime = $faker->dateTimeBetween('-1 year', '+1 year');
    $start = new Carbon($fakeDateTime);
    $end = $start->addHours(1);

    return [
        'topic_type' => CurationActivity::class,
        'topic_id' => CurationActivity::select('id')->get()->random()->id,
        'url' => $faker->url(),
        'starts_at' => $start,
        'ends_at' => $end,
        'notes' => $faker->paragraph(1),
        'invite_message' => $faker->paragraph(2),
    ];
});

$factory->state(TrainingSession::class, 'future', function ($faker) {
    $start = new Carbon($faker->dateTimeBetween('+1 day', '+1 year'));
    $end = $start->clone()->addHour();

    return [
        'starts_at' => $start,
        'ends_at' => $end,
    ];
});

$factory->state(TrainingSession::class, 'past', function ($faker) {
    $datetime = new Carbon($faker->dateTimeBetween('-1 year', '-1 day'));

    return [
        'starts_at' => $datetime,
        'ends_at' => $datetime->addHour(),
    ];
});
