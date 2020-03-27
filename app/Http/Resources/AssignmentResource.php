<?php

namespace App\Http\Resources;

use App\Http\Resources\TrainingResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AssignableResource;

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
        $data['assignable'] = new AssignableResource($this->whenLoaded('assignable'));
        $data['attestation'] = new attestationResource($this->whenLoaded('attestation'));
        $data['status'] = new DefaultResource($this->whenLoaded('status'));
        $data['trainings'] = TrainingResource::collection($this->whenLoaded('trainings'));
        $data['userAptitudes'] = DefaultResource::collection($this->whenLoaded('trainings'));
        $data['volunteer'] = new DefaultResource($this->whenLoaded('volunteer'));

        return $data;
    }
}
