<?php

namespace App\Jobs;

use App\assignable;
use App\Assignment;
use App\Contracts\AssignableContract;
use App\Exceptions\InvalidAssignmentException;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

class AssignVolunteerToAssignable
{
    use Dispatchable;
    use Queueable;

    protected $volunteer;

    protected $assignable;

    protected $assignmentDate;

    /**
     * Create a new job instance.
     *
     * @param  App\User  $volunteer  user to assign
     * @param  App\Contracts\AssignableContract  $assignable Assignable to assign
     * @return void
     */
    public function __construct(User $volunteer, AssignableContract $assignable, $assignmentDate = null)
    {
        $this->volunteer = $volunteer;
        $this->assignable = $assignable;
        $this->assignmentDate = $assignmentDate ?? Carbon::now();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if (
            $this->volunteer->volunteer_type_id === config('volunteers.types.baseline')
            && ! $this->assignable->canBeAssignedToBaseline()
        ) {
            throw new InvalidAssignmentException('Baseline volunteers can not be assigned to curations');
        }

        $data = [
            'user_id' => $this->volunteer->id,
            'assignable_id' => $this->assignable->id,
            'assignable_type' => get_class($this->assignable),
            'parent_id' => $this->getParentAssignmentId(),
        ];

        $asn = Assignment::firstOrCreate($data);
        $asn->created_at = $this->assignmentDate;
        $asn->updated_at = $this->assignmentDate;
    }

    private function getParentAssignmentId()
    {
        $parentAssignable = $this->assignable->getParentAssignable();
        $parentAssignment = $this->volunteer
            ->assignments()
            ->assignableIs(get_class($parentAssignable), $parentAssignable->id)
            ->first();

        return $parentAssignment?->id;
    }
}
