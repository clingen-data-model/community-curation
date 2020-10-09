<?php

namespace App\Traits;

use App\Priority;

trait StoresResponsePriorities
{
    private function storePriorities()
    {
        if (!$this->response->{'curation_activity_1'}) {
            return;
        }

        $prioritizationRound = Priority::selectRaw('max(prioritization_round) as prioritization_round')
                                ->where('user_id', $this->response->respondent_id)
                                ->get()
                                ->first()->prioritization_round ?? 0;
        ++$prioritizationRound;

        foreach ([1, 2, 3] as $num) {
            if ($this->response->{'curation_activity_'.$num}) {
                Priority::create([
                    'user_id' => $this->response->respondent_id,
                    'priority_order' => $num,
                    'curation_activity_id' => $this->response->{'curation_activity_'.$num},
                    'curation_group_id' => $this->response->{'panel_'.$num},
                    'activity_experience' => $this->response->{'activity_experience_'.$num} ?? 0,
                    'activity_experience_details' => $this->response->{'activity_experience_'.$num.'_detail'},
                    'effort_experience' => $this->response->{'effort_experience_'.$num} ?? 0,
                    'effort_experience_details' => $this->response->{'effort_experience_'.$num.'_detail'},
                    'outside_panel' => $this->response->outside_panel,
                    'prioritization_round' => $prioritizationRound,
                    'survey_id' => $this->response->survey_id,
                    'response_id' => $this->response->id,
                ]);
            }
        }
    }
}
