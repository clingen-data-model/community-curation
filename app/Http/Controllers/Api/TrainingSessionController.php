<?php

namespace App\Http\Controllers\Api;

use App\TrainingSession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingSessionRequest;

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

        return $query->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingSessionRequest $request)
    {
        $trainingSession = TrainingSession::create($request->except('csrf'));
        $trainingSession->load('topic');

        return $trainingSession;
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
        return $trainingSession;
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
        $trainingSession->update($request->all());
        $trainingSession->load('topic');

        return $trainingSession;
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
}
