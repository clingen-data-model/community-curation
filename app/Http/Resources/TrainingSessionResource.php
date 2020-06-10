<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // self::withoutWrapping();
        $data = parent::toArray($request);
        $data['calendar_links'] = $this->calendarLinks;

        return $data;
    }
}
