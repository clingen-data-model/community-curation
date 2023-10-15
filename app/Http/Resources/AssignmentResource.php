<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assignable_type' => $this->assignable_type,
            'assignment_status_id' => $this->assignment_status_id,
            'assignable' => new AssignableResource($this->whenLoaded('assignable')),
            'status' => new DefaultResource($this->whenLoaded('status')),
            'parent' => new AssignmentResource($this->whenLoaded('parent')),
            'sub_assignments' => AssignmentResource::collection($this->whenLoaded('subAssignments')),
            'user_aptitudes' => new DefaultResource($this->whenLoaded('userAptitudes')),
        ];
    }
}
