<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\TrainingSession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TrainingSessionRequest;
use App\Http\Resources\TrainingSessionResource;
use App\Notifications\TrainingSessionInviteEmail;

class TrainingSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = TrainingSession::query()
                    ->with('topic')
                    ->orderBy('starts_at');

        if ($request->scopes) {
            foreach ($request->scopes as $scope) {
                $query->$scope();
            }
        }
        
        if (!$request->scopes || !in_array('past', $request->scopes)) {
            $query->future();
        }

        return TrainingSessionResource::collection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingSessionRequest $request)
    {
        $data = $request->except('csrf');
        $data['starts_at'] = Carbon::parse($data['starts_at']);
        $data['ends_at'] = Carbon::parse($data['ends_at']);

        $trainingSession = TrainingSession::create($data);
        $trainingSession->load('topic');

        return new TrainingSessionResource($trainingSession);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trainingSession  = TrainingSession::findOrFail($id);
        $trainingSession->load('topic');
        return new TrainingSessionResource($trainingSession);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(TrainingSessionRequest $request, $id)
    {
        $trainingSession = TrainingSession::findOrFail($id);

        $data = $request->all();
        $data['starts_at'] = Carbon::parse($data['starts_at']);
        $data['ends_at'] = Carbon::parse($data['ends_at']);
        $trainingSession->update($data);
        $trainingSession->load('topic');

        return new TrainingSessionResource($trainingSession);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TrainingSession  $trainingSession
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TrainingSession::findOrFail($id)->delete();
    }

    public function inviteEmailPreview($id)
    {
        $trainingSession = TrainingSession::findOrFail($id);

        return (new TrainingSessionInviteEmail($trainingSession))
                    ->toMail(Auth::user());
    }
}
