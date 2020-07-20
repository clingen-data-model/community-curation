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
        $data['curation_activity'] =  new DefaultResource($this->whenLoaded('curationActivity'));
        $data['curation_group'] = new DefaultResource($this->whenLoaded('curationGroup'));

        $data['outside_panel'] = 'No';
        if ($this->outside_panel == 2) {
            $data['outside_panel'] = 'Maybe';
        } elseif ($this->outside_panel == 1) {
            $data['outside_panel'] = 'Yes';
        }

        return $data;
    }
}
