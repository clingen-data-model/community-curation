<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = parent::toArray($request);
        $data['aptitude'] = new DefaultResource($this->whenLoaded('aptitude'));
        $data['assignment'] = new DefaultResource($this->whenLoaded('assignment'));
        $data['user'] = new DefaultResource($this->whenLoaded('user'));

        return $data;
    }
}
