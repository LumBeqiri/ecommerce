<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
/** @mixin \App\Models\VariantPrice */
class VariantPriceResource extends JsonResource
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
            /** @phpstan-ignore-next-line */
            'id' => $this->uuid,
            'price' => $this->price,
            'variant' => new VariantResource($this->whenLoaded('variant')),
            'region' => new RegionResource($this->region),
            'min_quantity' => $this->min_quantity,
            'max_quantity' => $this->max_quantity,
        ];
    }
}
