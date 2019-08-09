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
        $array['volunteer_status'] = new DefaultResource($this->whenLoaded('volunteerStatus'));
        $array['volunteer_type'] = new DefaultResource($this->whenLoaded('volunteerType'));
        
        return $array;
    }
}
