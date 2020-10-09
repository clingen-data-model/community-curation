<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NotImplementedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingCreateRequest;
use App\Http\Resources\DefaultResource;
use App\UserAptitude;
use Illuminate\Http\Request;

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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingCreateRequest $request)
    {
        $training = UserAptitude::create($request->all());

        return new DefaultResource($training);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        throw new NotImplementedException();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        throw new NotImplementedException();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $training = UserAptitude::find($id);

        $training->update($request->all());

        return new DefaultResource($training);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        throw new NotImplementedException();
    }
}
