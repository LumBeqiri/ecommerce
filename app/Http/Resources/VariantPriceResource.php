<?php

namespace App\Http\Resources;

use App\Services\PriceService;
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
            'id' => $this->ulid,
            'price' => PriceService::variantPriceToDisplay($this->resource),
            'variant' => new VariantResource($this->whenLoaded('variant')),
            'region' => new RegionResource($this->region),
            'currency' => $this->currency->code,
            'min_quantity' => $this->min_quantity,
            'max_quantity' => $this->max_quantity,
        ];
    }
}
