<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Country;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        // 'country_id' => Country::all()->random()->id,
        'timezone' => 'America/New_York',
        'hypothesis_id' => Arr::random([null, uniqid(10)]),
    ];
});

// Volunteer state
$factory->state(User::class, 'volunteer', function (Faker $faker) {
    return [
        'volunteer_type_id' => 1,
        'volunteer_status_id' => 1
    ];
});
$factory->afterCreatingState(User::class, 'volunteer', function ($user, $faker) {
    $user->syncRoles(['volunteer']);
});

$factory->state(User::class, 'baseline', function (Faker $faker) {
    return [
        'volunteer_type_id' => 1
    ];
});

$factory->state(User::class, 'comprehensive', function (Faker $faker) {
    return [
        'volunteer_type_id' => 2
    ];
});

// Programmer state
$factory->state(User::class, 'programmer', function (Faker $faker) {
    return [
        'volunteer_type_id' => null,
        'volunteer_status_id' => null
    ];
});
$factory->afterCreatingState(User::class, 'programmer', function ($user, $faker) {
    $user->assignRole('programmer');
});

// Admin state
$factory->state(User::class, 'admin', function (Faker $faker) {
    return [
        'volunteer_type_id' => null,
        'volunteer_status_id' => null
    ];
});
$factory->afterCreatingState(User::class, 'admin', function ($user, $faker) {
    $user->assignRole('admin');
});

$factory->state(User::class, 'super-admin', function (Faker $faker) {
    return [
        'volunteer_type_id' => null,
        'volunteer_status_id' => null
    ];
});
$factory->afterCreatingState(User::class, 'super-admin', function ($user, $faker) {
    $user->assignRole('super-admin');
});

// Coordinator state
$factory->state(User::class, 'coordinator', function (Faker $faker) {
    return [
        'volunteer_type_id' => null,
        'volunteer_status_id' => null
    ];
});
$factory->afterCreatingState(User::class, 'coordinator', function ($user, $faker) {
    $user->assignRole('coordinator');
});
