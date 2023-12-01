<?php

namespace App\Console\Commands;

use App\Application;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanBlankApplications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applications:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force-deletes all applications that are older than the session experiation time and do not have any data.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $empties = Application::where('created_at', '<', Carbon::now()->subMinutes(config('session.lifetime')))
        ->noUserData()
        ->get();

        $empties->each(function ($app) {
            $app->forceDelete();
        });

        $this->info('Deleted '.$empties->count().' empty applications');
        \Log::info('Deleted '.$empties->count().' empty applications');
    }
}
