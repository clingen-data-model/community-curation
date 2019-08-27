<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Assignment;
use App\ExpertPanel;
use App\CurationActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\AssignVolunteerToAssignable;
use App\Http\Resources\AssignmentResource;
use App\Http\Requests\ActivityAssignmentCreateRequest;

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
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Assignment $assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment)
    {
        //
    }
}
