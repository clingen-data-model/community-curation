<?php

namespace App\Http\Controllers;

use App\CurationActivity;
use Illuminate\View\View;

class CurationActivityController extends Controller
{
    public function index(): View
    {
        $curationActivities = CurationActivity::all();

        return view('curation_activities.index', compact('curationActivities'));
    }

    public function show($id)
    {
        $curationActivity = CurationActivity::findOrFail($id);
        $curationActivity->load(
            'assignments',
            'assignments.subAssignments',
            'assignments.status',
            'assignments.volunteer',
            'assignments.volunteer.volunteerStatus',
            'assignments.volunteer.country',
            'assignments.userAptitudes',
            'assignments.volunteer.application.selfDescription'
        );

        $curationActivity->assignments = $curationActivity->assignments->map(function ($asn) {
            $asn->user_aptitude = $asn->userAptitudes->first();

            return $asn;
        });

        return view('curation_activities.show', compact('curationActivity'));
    }
}
