<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Region */
class RegionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            // @phpstan-ignore-next-line
            'id' => $this->uuid,
            'title' => $this->title,
            'currency' => $this->currency,
            'tax_rate' => $this->tax_rate,
            'tax_code' => $this->tax_code,
            'tax_provider' => $this->tax_provider_id,

        ];
    }
}
