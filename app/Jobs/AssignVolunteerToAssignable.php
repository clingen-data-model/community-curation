<?php

namespace App\Jobs;

use App\Assignment;
use App\Contracts\AssignableContract;
use App\User;
use App\assignable;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use InvalidArgumentException;

class AssignVolunteerToAssignable
{
    use Dispatchable, Queueable;

    protected $volunteer;

    protected $assignable;

    /**
     * Create a new job instance.
     *
     * @param App\User $volunteer user to assign
     * @param App\Contracts\AssignableContract $assignable Assignable to assign
     * 
     * @return void
     */
    public function __construct(User $volunteer, AssignableContract $assignable)
    {
        $this->volunteer = $volunteer;
        $this->assignable = $assignable;
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
            'assignable_id' => $this->assignable->id,
            'assignable_type' => get_class($this->assignable)
        ]);
    }
}
