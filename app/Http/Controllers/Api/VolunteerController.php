<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VolunteerRequest;
use App\Exceptions\NotImplementedException;
use App\Http\Resources\VolunteerUserResource;

class VolunteerController extends Controller
{
    protected $validFilters = [
        'first_name',
        'last_name',
        'name',
        'volunteer_status_id',
        'volunteer_type_id',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = ($request->has('perPage') && !is_null($request->perPage)) ? $request->perPage : 25;

        $query = User::query()
                        ->with([
                            'volunteerType',
                            'volunteerStatus',
                            'structuredAssignments',
                        ])
                        ->isVolunteer();

        foreach ($request->all() as $key => $value) {
            if ($key == 'with') {
                $query->with($value);
            }
            if (in_array($key, $this->validFilters)) {
                $query->where($key, $value);
            }

            if ($key == 'expert_panel_id') {
                if ($value == -1) {
                    $query->comprehensive()->whereDoesntHave('assignments', function ($q) {
                        $q->expertPanel();
                    });
                } else {
                    $query->whereHas('assignments', function ($q) use ($value) {
                        $q->where([
                            'assignable_type' => 'App\ExpertPanel',
                            'assignable_id' => $value
                        ]);
                    });
                }
            }
            if ($key == 'curation_activity_id') {
                if ($value == -1) {
                    $query->doesntHave('assignments');
                } else {
                    $query->whereHas('assignments', function ($q) use ($value) {
                        $q->where([
                            'assignable_type' => 'App\CurationActivity',
                            'assignable_id' => $value
                        ]);
                    });
                }
            }
            if ($key == 'gene_id') {
                $query->whereHas('assignments', function ($q) use ($value) {
                    $q->where([
                        'assignable_type' => 'App\Gene',
                        'assignable_id' => $value
                    ]);
                });
            }
        }

        if (!is_null($request->searchTerm)) {
            $query->leftJoin('volunteer_statuses', 'users.volunteer_status_id', '=', 'volunteer_statuses.id')
                ->leftJoin('volunteer_types', 'users.volunteer_type_id', '=', 'volunteer_types.id')
                ->select('users.*')
                ;
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->searchTerm.'%')
                ->orWhere('last_name', 'like', '%'.$request->searchTerm.'%')
                ->orWhere('email', 'like', '%'.$request->searchTerm.'%')
                ->orWhere('volunteer_statuses.name', 'like', '%'.$request->searchTerm.'%')
                ->orWhere('volunteer_types.name', 'like', '%'.$request->searchTerm.'%')
                ;
            });
        }

        $sortField = ($request->sortBy) ?? 'last_name';
        $sortDir = ($request->sortDesc === 'true') ? 'desc' : 'asc';
        $query->orderBy($sortField, $sortDir);

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
            'structuredAssignments',
            'structuredAssignments.userAptitudes', // with moved here for more efficient index
            'structuredAssignments.userAptitudes.aptitude', // with moved here for more efficient index
            'structuredAssignments.userAptitudes.attestation', // with moved here for more efficient index
            'structuredAssignments.assignable.aptitudes', // with moved here for more efficient index
            'attestations',
            'attestations.aptitude',
            'priorities',
            'priorities.curationActivity',
            'priorities.expertPanel',
            'volunteer3MonthSurvey',
            'volunteer6MonthSurvey'
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
    public function update(VolunteerRequest $request, $id)
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
