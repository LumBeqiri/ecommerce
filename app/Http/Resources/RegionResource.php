<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Region */
class RegionResource extends JsonResource
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
            'title' => $this->title,
            'tax_rate' => $this->tax_rate,
            'tax_code' => $this->tax_code,
            'tax_provider' => $this->tax_provider_id,

        ];
    }
}
