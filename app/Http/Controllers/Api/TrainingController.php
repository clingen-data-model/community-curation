<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NotImplementedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingCreateRequest;
use App\Http\Requests\UpdateAssignedTrainingRequest;
use App\Http\Resources\DefaultResource;
use App\UserAptitude;

class TrainingController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        throw new NotImplementedException();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingCreateRequest $request)
    {
        $userAptitude = UserAptitude::create($request->all());

        return new DefaultResource($userAptitude);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        throw new NotImplementedException();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        throw new NotImplementedException();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssignedTrainingRequest $request, int $id)
    {
        $userAptitude = UserAptitude::find($id);

        $userAptitude->update($request->all());

        return new DefaultResource($userAptitude);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        throw new NotImplementedException();
    }
}
