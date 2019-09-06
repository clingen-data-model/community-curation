<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PriorityResource extends JsonResource
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
        // $data['volunteer'] = new DefaultResource($this->whenLoaded('user'));
        $data['curation_activity'] =  new DefaultResource($this->whenLoaded('curationActivity'));
        $data['expert_panel'] = new DefaultResource($this->whenLoaded('expertPanel'));

        return $data;
    }
}
