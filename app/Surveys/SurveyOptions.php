<?php

namespace App\Surveys;

use App\Goal;
use App\Campaign;
use App\Interest;
use App\Motivation;
use App\SelfDescription;
use App\CurationActivity;

class SurveyOptions
{
    public function timezones()
    {
        $zones = timezone_abbreviations_list();
        $options = [];
        $count = 1;
        foreach($zones as $zone => $items) {
            if (strlen($zone) == 1) {
                continue;
            }
            $options[] = (object)[
                'id' => $count,
                'name' => $zone.' ('.$items[0]['timezone_id'].')'
            ];
            $count++;
        }
        return $options;
    }
    
    public function curationActivities()
    {
        return CurationActivity::select('id', 'name')->get();
    }
    

    public function expertPanels()
    {
        return $this->getDummyData();
    }

    public function selfDescriptions()
    {
        return SelfDescription::select('id', 'name')->get();
    }

    public function adCampaigns()
    {
        return Campaign::select('id', 'name')->get();
    }

    public function motivations()
    {
        return Motivation::select('id', 'name')->get();
    }

    public function interests()
    {
        return Interest::select('id', 'name')->get();
    }
    
    public function goals()
    {
        return Goal::select('id', 'name')->get();
    }

    private function getDummyData()
    {
        return (object)[
            [
                'id' => 1,
                'name' => 'a'
            ],
            [
                'id' => 2,
                'name' => 'b'
            ],
        ];
    }
    
}
