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

        $prioritizationRound = Priority::selectRaw('max(prioritization_round)')
                                ->where('user_id', $this->response->respondent_id)
                                ->first()->prioritization_round++ ?? 1;

        foreach([1,2,3] as $num) {
            if ($this->response->{'curation_activity_'.$num}) {
                $p = Priority::create([
                    'user_id' => $this->response->respondent_id,
                    'priority_order' => $num,
                    'curation_activity_id' => $this->response->{'curation_activity_'.$num},
                    'expert_panel_id' => $this->response->{'panel_'.$num},
                    'activity_experience' => $this->response->{'activity_experience_'.$num} ?? 0,
                    'activity_experience_details' => $this->response->{'activity_experience_'.$num.'_detail'},
                    'effort_experience' => $this->response->{'effort_experience_'.$num} ?? 0,
                    'effort_experience_details' => $this->response->{'effort_experience_'.$num.'_detail'},
                    'prioritization_round' => $prioritizationRound,
                ]);
            }
        }
    }
}

?>