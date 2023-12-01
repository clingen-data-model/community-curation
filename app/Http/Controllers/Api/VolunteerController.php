<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NotImplementedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contracts\VolunteerRequestContract;
use App\Http\Resources\VolunteerUserResource;
use App\Policies\VolunteerPolicy;
use App\Services\Search\VolunteerSearchService;
use App\User;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    protected $searchService;

    protected $policy;

    public function __construct(VolunteerSearchService $searchService, VolunteerPolicy $policy)
    {
        $this->searchService = $searchService;
        $this->policy = $policy;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = ($request->has('perPage') && ! is_null($request->perPage)) ? $request->perPage : 25;

        $query = $this->searchService->buildQuery($request->all());

        $volunteers = ($request->has('page'))
                        ? $query->paginate($pageSize)
                        : $query->get();

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
        $volunteer->member_groups = $volunteer->member_groups;

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
    public function update(VolunteerRequestContract $request, $id)
    {
        $volunteer = User::findOrFail($id);
        if (! $this->policy->update(\Auth::user(), $volunteer)) {
            return response('Unauthorized', 403);
        }

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
