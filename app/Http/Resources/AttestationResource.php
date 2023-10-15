<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttestationResource extends JsonResource
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

        return $data;
    }
}
