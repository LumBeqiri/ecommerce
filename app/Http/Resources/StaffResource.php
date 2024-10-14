<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->ulid,
            'position' => $this->position,
            'phone' => $this->phone,
            'status' => $this->status,
            'notes' => $this->notes,
            'address' => $this->address,
            'vendor_id' => $this->vendor_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user' => new UserResource($this->user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
