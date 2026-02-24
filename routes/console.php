<?php

use App\Jobs\Dev\TestJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

if (env('TEST_SCHEDULER')) {
    Schedule::call(function () {
        Log::info('testing scheduler');
        TestJob::dispatch();
    })->everyMinute();
}

Schedule::command('volunteers:notify-followup')->dailyAt('01:00');
Schedule::command('applications:clean')->dailyAt('00:01');
