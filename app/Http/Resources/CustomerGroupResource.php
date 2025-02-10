<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CustomerGroup */
class CustomerGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->ulid,
            'name' => $this->name,
            'metadata' => $this->metadata ?? '',
            'seller' => new UserResource($this->user),
        ];
    }
}
