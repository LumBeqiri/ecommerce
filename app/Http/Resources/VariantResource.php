<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Variant */
class VariantResource extends JsonResource
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
            'product' => new ProductResource($this->whenLoaded('product')),
            'sku' => $this->sku,
            'variant_name' => $this->variant_name,
            'variant_short_description' => $this->variant_short_description,
            'variant_long_description' => $this->variant_long_description,
            'stock' => $this->stock,
            'status' => $this->status,
            'price' => VariantPriceResource::collection($this->whenLoaded('variant_prices')),
            'medias' => MediaResource::collection($this->getMedia()),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
        ];
    }
}
