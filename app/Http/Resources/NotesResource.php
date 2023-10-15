<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotesResource extends JsonResource
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
        $data['notable'] = $this->whenLoaded('notable');
        $data['creator'] = $this->whenLoaded('creator');
        $data['created_at'] = $this->created_at->format('Y-m-d\TH:i:s\Z');
        $data['updated_at'] = $this->created_at->format('Y-m-d\TH:i:s\Z');

        return $data;
    }
}
