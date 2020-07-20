<?php

namespace App\Surveys;

use App\Goal;
use App\Campaign;
use App\Interest;
use App\Motivation;
use App\SelfDescription;
use App\CurationActivity;
use App\CurationGroup;

class SurveyOptions
{
    public function timezones()
    {
        $zones = timezone_identifiers_list();
        $options = [];
        foreach ($zones as $zone) {
            $options[] = (object)[
                'name' => $zone,
                'id' => $zone,
            ];
        }
        return $options;
    }
    
    public function curationActivities()
    {
        return CurationActivity::select('id', 'name')->comprehensive()->get();
    }
    
    public function curationGroups()
    {
        $curationGroups = CurationGroup::select('id', 'name', 'accepting_volunteers')->acceptingVolunteers()->get();
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
