<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VolunteerUserResource;
use App\Exceptions\NotImplementedException;

class VolunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $volunteerQuery = User::query()
                        ->with(
                            'volunteerType', 
                            'volunteerStatus', 
                            'assignments',
                            'priorities',
                            'priorities.curationActivity',
                            'priorities.expertPanel'
                        )
                        ->isVolunteer();



        $volunteers = $volunteerQuery->get();
      
        return VolunteerUserResource::collection($volunteers);
    }

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        throw new NotImplementedException();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $volunteer = User::findOrFail($id);
        $volunteer->load([
            'volunteerStatus',
            'volunteerType',
            'application',
            'assignments',
            'attestations',
            'attestations.aptitude',
            'priorities',
            'priorities.curationActivity',
            'priorities.expertPanel',
        ]);

        return new VolunteerUserResource($volunteer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        throw new NotImplementedException();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $volunteer = User::findOrFail($id);
        $volunteer->update($request->all());

        return new VolunteerUserResource($volunteer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        throw new NotImplementedException();
    }
}
