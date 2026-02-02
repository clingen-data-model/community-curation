<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $array = parent::toArray($request);
        unset($array['email_verified_at']);
        unset($array['deleted_at']);
        $array['volunteer_status'] = new DefaultResource($this->whenLoaded('volunteerStatus'));
        $array['volunteer_type'] = new DefaultResource($this->whenLoaded('volunteerType'));
        $array['assignments'] = AssignmentResource::collection($this->whenLoaded('structuredAssignments'));
        $array['application'] = new SurveyResponseResource($this->whenLoaded('application'));
        $threeMonth = $this->relationLoaded('volunteer3MonthSurveyJson') && $this->volunteer3MonthSurveyJson
            ? $this->volunteer3MonthSurveyJson
            : $this->whenLoaded('volunteer3MonthSurvey');
        $sixMonth = $this->relationLoaded('volunteer6MonthSurveyJson') && $this->volunteer6MonthSurveyJson
            ? $this->volunteer6MonthSurveyJson
            : $this->whenLoaded('volunteer6MonthSurvey');
        $array['three_month'] = new SurveyResponseResource($threeMonth);
        $array['six_month'] = new SurveyResponseResource($sixMonth);
        $array['latest_priorities'] = $this->relationLoaded('priorities')
                                        ? PriorityResource::collection($this->latestPriorities)->sortBy('priority_order')
                                        : null;
        $array['priorities'] = PriorityResource::collection($this->whenLoaded('priorities'));

        unset($array['structured_assignments']);
        unset($array['volunteer3_month_survey_json']);
        unset($array['volunteer6_month_survey_json']);

        return $array;
    }
}
