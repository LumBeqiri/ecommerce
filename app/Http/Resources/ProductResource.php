<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
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
            'product_name' => $this->product_name,
            'vendor' => new VendorResource($this->whenLoaded('vendor')),
            'status' => $this->status,
            // use spatie media to get the thumbnail
            'thumbnail' => $this->getMedia('thumbnails'),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'price' => VariantPriceResource::collection($this->whenLoaded('variant_prices')),
            'variants' => VariantResource::collection($this->whenLoaded('variants')),
            'publish_status' => $this->publish_status,
        ];
    }
}
