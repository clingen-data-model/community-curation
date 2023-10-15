<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurationGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['curation_activities'] = $this->whenLoaded('curationActivity');
        $data['working_group'] = $this->whenLoaded('workingGroup');

        return $data;
    }
}
