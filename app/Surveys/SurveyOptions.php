<?php

namespace App\Surveys;

use App\Campaign;
use App\CurationActivity;
use App\CurationGroup;
use App\Goal;
use App\Interest;
use App\Motivation;
use App\SelfDescription;

class SurveyOptions
{
    public function timezones()
    {
        $zones = timezone_identifiers_list();
        $options = [];
        foreach ($zones as $zone) {
            $options[] = (object) [
                'name' => $zone,
                'id' => $zone,
            ];
        }

        return $options;
    }

    public function curationActivities()
    {
        return CurationActivity::select('id', 'name')->comprehensive()->whereHas('curationGroups', function ($q) { $q->acceptingVolunteers(); })->get();
    }

    public function acceptingCurationGroups()
    {
        $curationGroups = CurationGroup::select('id', 'name', 'accepting_volunteers')
                            ->acceptingVolunteers()
                            ->orderBy('name')
                            ->get();

        return $curationGroups;
    }

    public function allCurationGroups()
    {
        $curationGroups = CurationGroup::select('id', 'name', 'accepting_volunteers')
                            ->orderBy('name')
                            ->get();

        return $curationGroups;
    }

    public function selfDescriptions()
    {
        return SelfDescription::select('id', 'name')->withoutOther()->get();
    }

    public function adCampaigns()
    {
        return Campaign::select('id', 'name')->withoutOther()->get();
    }

    public function motivations()
    {
        return Motivation::select('id', 'name')->withoutOther()->get();
    }

    public function interests()
    {
        return Interest::select('id', 'name')->get();
    }

    public function goals()
    {
        return Goal::select('id', 'name')->withoutOther()->get();
    }
}
