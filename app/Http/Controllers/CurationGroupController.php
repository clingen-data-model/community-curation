<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\CurationGroup;
use App\Services\Search\VolunteerSearchService;

class CurationGroupController extends Controller
{
    protected $volunteerSearch;

    public function __construct(VolunteerSearchService $volunteerSearch)
    {
        $this->volunteerSearch = $volunteerSearch;
    }

    public function index(): View
    {
        return view('curation-groups.index');
    }

    public function show($id)
    {
        $curationGroup = CurationGroup::findOrFail($id)
            ->load([
                'curationActivity',
                'workingGroup',
                'assignments',
                'assignments.parent',
                'assignments.status',
                'assignments.volunteer',
                'assignments.volunteer.volunteerStatus',
                'assignments.volunteer.country',
                'assignments.volunteer.userAptitudes',
                'assignments.volunteer.application.selfDescription',
            ]);
        $curationGroup->assignments = $curationGroup->assignments->map(function ($ass) use ($curationGroup) {
            $ass->user_aptitude = $ass->volunteer->userAptitudes->filter(function ($ua) use ($curationGroup) {
                return $ua->aptitude->subject_type = \App\CurationActivity::class
                                                && $ua->aptitude->subject_id = $curationGroup->curation_activity_id;
            })->first();

            return $ass;
        });

        return view('curation-groups.show', compact('curationGroup'));
    }
}
