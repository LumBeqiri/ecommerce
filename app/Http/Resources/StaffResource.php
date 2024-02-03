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
            'id' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'position' => $this->position,
            'phone' => $this->phone,
            'city' => $this->city,
            'status' => $this->status,
            'notes' => $this->notes,
            'address' => $this->address,
            'vendor_id' => $this->vendor_id,
            'country_id' => $this->country_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user_id' => new UserResource($this->user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
