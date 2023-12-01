<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingSessionAttendeeResource extends JsonResource
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
        if ($this->assignments->count() > 0) {
            $data['training_complete'] = (bool) $this->assignments->first()->userAptitudes->first()->trained_at;
        }

        return $data;
    }
}
