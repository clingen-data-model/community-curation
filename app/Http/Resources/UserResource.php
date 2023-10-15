<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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

        $data['roles'] = DefaultResource::collection($this->whenLoaded('roles'));
        $data['permissions'] = DefaultResource::collection($this->whenLoaded('permissions'));
        $data['country'] = DefaultResource::collection($this->whenLoaded('country'));

        return $data;
    }
}
