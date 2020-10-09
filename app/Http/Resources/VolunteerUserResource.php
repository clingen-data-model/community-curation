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
        $array['three_month'] = new SurveyResponseResource($this->whenLoaded('volunteer3MonthSurvey'));
        $array['six_month'] = new SurveyResponseResource($this->whenLoaded('volunteer6MonthSurvey'));
        $array['latest_priorities'] = $this->relationLoaded('priorities')
                                        ? PriorityResource::collection($this->latestPriorities)->sortBy('priority_order')
                                        : null;
        $array['priorities'] = PriorityResource::collection($this->whenLoaded('priorities'));

        unset($array['structured_assignments']);

        return $array;
    }
}
