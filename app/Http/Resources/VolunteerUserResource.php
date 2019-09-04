<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = parent::toArray($request);
        unset($array['email_verified_at']);
        unset($array['deleted_at']);
        $array['volunteer_status'] = new DefaultResource($this->whenLoaded('volunteerStatus'));
        $array['volunteer_type'] = new DefaultResource($this->whenLoaded('volunteerType'));
        $array['assignments'] =  $this->structuredAssignments;
        $array['application'] = new DefaultResource($this->whenLoaded('application'));
        $array['latest_priorities'] = PriorityResource::collection($this->whenLoaded('latestPriorities'));
        $array['priorities'] = PriorityResource::collection($this->whenLoaded('priorities'));
        
        return $array;
    }
}
