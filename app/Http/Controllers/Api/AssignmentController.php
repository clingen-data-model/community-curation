<?php

namespace App\Http\Controllers\Api;

use App\Assignment;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityAssignmentCreateRequest;
use App\Http\Requests\AssignmentUpdateRequest;
use App\Http\Resources\AssignmentResource;
use App\Jobs\AssignVolunteerToAssignable;
use App\User;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        return AssignmentResource::collection(Assignment::all());
    }

    public function volunteer(Request $request, $id)
    {
        $volunteer = User::findOrFail($id);

        return $volunteer->structuredAssignments;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ActivityAssignmentCreateRequest $request)
    {
        $volunteer = User::find($request->user_id);
        $assignable = ($request->assignable_type)::find($request->assignable_id);

        AssignVolunteerToAssignable::dispatch($volunteer, $assignable);
        $newAssignment = Assignment::orderBy('id', 'desc')
                            ->limit(1)
                            ->first();
        $newAssignment->load(['volunteer', 'assignable', 'status']);
        // return $newAssignment;

        return new AssignmentResource($newAssignment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(AssignmentUpdateRequest $request, Assignment $assignment)
    {
        $assignment->update(['assignment_status_id' => $request->assignment_status_id]);
        $assignment->load(['volunteer', 'assignable', 'status']);

        return new AssignmentResource($assignment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment)
    {
        $assignment->delete();

        return $assignment->volunteer->structuredAssignments;
    }
}
