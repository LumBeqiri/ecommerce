<?php

namespace App\Http\Resources;

use Attribute;
use App\Models\Media;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\VariantPriceResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->uuid,
            'product' => new ProductResource($this->whenLoaded('product')),
            'sku' => $this->sku,
            'variant_name' => $this->variant_name,
            'variant_short_description' => $this->variant_short_description,
            'variant_long_description' => $this->variant_long_description,
            'stock' => $this->stock,
            'status' => $this->status,
            'price' => VariantPriceResource::collection($this->variant_prices),
            'medias' => MediaResource::collection($this->medias),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes'))
        ];
    }
}
