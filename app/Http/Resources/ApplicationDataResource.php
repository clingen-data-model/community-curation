<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data['country'] = ($this->country) ? $this->country->name : null;

        $data['volunteer_type'] = ($this->volunteerType) ? $this->volunteerType->name : null;
        $data['self_description'] = ($this->selfDescription) ? $this->selfDescription->name : null;

        unset($data['country_id'], $data['self_desc']);

        unset($data['survey_id'], $data['survey']);
        unset($data['curation_activity_1']);
        unset($data['panel_1']);
        unset($data['effort_experience_1']);
        unset($data['effort_experience_1_detail']);
        unset($data['activity_experience_1']);
        unset($data['activity_experience_1_detail']);
        unset($data['outside_panel']);
        unset($data['additional_priority']);

        unset($data['curation_activity_2']);
        unset($data['panel_2']);
        unset($data['effort_experience_2']);
        unset($data['effort_experience_2_detail']);
        unset($data['activity_experience_2']);
        unset($data['activity_experience_2_detail']);

        unset($data['curation_activity_3']);
        unset($data['panel_3']);
        unset($data['effort_experience_3']);
        unset($data['effort_experience_3_detail']);
        unset($data['activity_experience_3']);
        unset($data['activity_experience_3_detail']);
        unset($data['created_at'], $data['updated_at']);

        return $data;
    }
}
