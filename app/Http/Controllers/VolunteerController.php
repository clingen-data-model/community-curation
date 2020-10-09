<?php

namespace App\Http\Controllers;

use App\Exceptions\NotImplementedException;
use App\Http\Resources\VolunteerUserResource;
use App\User;
use App\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole('volunteer')) {
            return redirect('/volunteers/'.Auth::user()->id);
        }

        return view('volunteers.index');
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        throw new NotImplementedException();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Volunteer $volunteer
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $volunteer)
    {
        if (Auth::user()->hasRole('volunteer') && Auth::user()->id !== $volunteer->id) {
            return redirect('/volunteers/'.Auth::user()->id);
        }
        $volunteer->load([
            'volunteerStatus',
            'volunteerType',
            'application',
            'structuredAssignments',
            'structuredAssignments.userAptitudes', // with moved here for more efficient index
            'structuredAssignments.userAptitudes.aptitude', // with moved here for more efficient index
            'structuredAssignments.userAptitudes.attestation', // with moved here for more efficient index
            'structuredAssignments.assignable.aptitudes', // with moved here for more efficient index
            'attestations',
            'attestations.aptitude',
            'priorities',
            'priorities.curationActivity',
            'priorities.curationGroup',
            'volunteer3MonthSurvey',
            'volunteer6MonthSurvey',
        ]);

        return view('volunteers.detail', ['volunteerId' => $volunteer->id, 'volunteerJson' => json_encode(new VolunteerUserResource($volunteer))]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Volunteer $volunteer
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $volunteer)
    {
        throw new NotImplementedException();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Volunteer $volunteer
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $volunteer)
    {
        throw new NotImplementedException();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Volunteer $volunteer
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $volunteer)
    {
        throw new NotImplementedException();
    }
}
