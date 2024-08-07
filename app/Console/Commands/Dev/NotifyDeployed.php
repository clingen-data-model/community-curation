<?php

namespace App\Console\Commands\Dev;

use App\Notifications\Dev\Deployed;
use App\User;
use Illuminate\Console\Command;

class NotifyDeployed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:deployed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a slack notification that a new pod is being spun up.';

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
        # FIXME : failed if user with id 1 is not active...
        /*
        User::whereHas("roles", function ($q) { $q->where("name", "programmer"); })->each(function ($user) {
            $user->notify(new Deployed());
        });
         */
    }
}
