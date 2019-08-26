<?php

namespace App\Jobs;

use App\Assignment;
use App\User;
use App\CurationActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use InvalidArgumentException;

class AssignVolunteerToAssignable
{
    use Dispatchable, Queueable;

    protected $volunteer;

    protected $curationActivity;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $volunteer, CurationActivity $curationActivity)
    {
        $this->volunteer = $volunteer;
        $this->curationActivity = $curationActivity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->volunteer->volunteer_type_id === config('volunteers.types.baseline')) {
            throw new InvalidArgumentException('Baseline volunteers can not be assigned to curations');
        }

        Assignment::firstOrCreate([
            'user_id' => $this->volunteer->id,
            'assignable_id' => $this->curationActivity->id,
            'assignable_type' => get_class($this->curationActivity)
        ]);
    }
}
