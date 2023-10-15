<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // self::withoutWrapping();
        $data = parent::toArray($request);
        $data['calendar_links'] = $this->calendarLinks;
        $data['starts_at'] = $this->starts_at->format('Y-m-d\TH:i:s\Z');
        $data['ends_at'] = $this->ends_at->format('Y-m-d\TH:i:s\Z');

        return $data;
    }
}
