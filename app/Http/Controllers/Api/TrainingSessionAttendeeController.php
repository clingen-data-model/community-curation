<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\TrainingSession;
use App\CurationActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultResource;
use App\Exceptions\NotImplementedException;
use App\Http\Requests\TrainingSessionAttendeeInviteRequest;

class TrainingSessionAttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($trainingSessionId)
    {
        $trainingSession = TrainingSession::findOrFail($trainingSessionId);
        $attendees = $trainingSession->attendees()->with(['assignments' => function ($q) use ($trainingSession) {
            $q->assignableIs($trainingSession->topic_type, $trainingSession->topic_id)
                ->select('created_at as date_assigned', 'user_id');
        }])->get();

        return new DefaultResource($attendees);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingSessionAttendeeInviteRequest $request, $trainingSessionId)
    {
        $trainingSession = TrainingSession::findOrFail($trainingSessionId);
        $trainingSession->attendees()->syncWithoutDetaching($request->attendee_ids);

        $attendees = $trainingSession->attendees()->with(['assignments' => function ($q) use ($trainingSession) {
            $q->assignableIs($trainingSession->topic_type, $trainingSession->topic_id)
                ->select('created_at as date_assigned', 'user_id');
        }])->get();

        return new DefaultResource($attendees);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new NotImplementedException();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($trainingSessionId, $userId)
    {
        $trainingSession = TrainingSession::findOrFail($trainingSessionId);
        
        $trainingSession->attendees()->detach($userId);
    }
    
    public function trainableVolunteers($trainingSessionId)
    {
        $trainingSession = TrainingSession::findOrFail($trainingSessionId);
        $volunteers = User::isVolunteer()
                        ->whereNotIn('id', $trainingSession->attendees->pluck('id'))
                        ->whereHas('userAptitudes', function ($q) use ($trainingSession) {
                            $q->needsTraining()
                                ->whereHas('aptitude', function ($qu) use ($trainingSession) {
                                    $qu->forSubject($trainingSession->topic_type, $trainingSession->topic_id);
                                });
                        })
                        ->with(['assignments' => function ($q) use ($trainingSession) {
                            $q->assignableIs($trainingSession->topic_type, $trainingSession->topic_id)
                                ->select('created_at as date_assigned', 'user_id');
                        }])
                        ->get();
        return DefaultResource::collection($volunteers);
    }
}
