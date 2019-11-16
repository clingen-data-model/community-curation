<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['volunteer'] = new DefaultResource($this->whenLoaded('volunteer'));
        $data['assignable'] = new DefaultResource($this->whenLoaded('assignable'));
        $data['status'] = new DefaultResource($this->whenLoaded('status'));
        $data['training'] = new DefaultResource($this->training);

        return $data;
    }
}
