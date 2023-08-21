<?php

namespace App\Http\Resources;

use App\Http\Resources\VariantResource;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
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
            'product_name' => $this->product_name,
            'long_description' => $this->product_long_description,
            'product_short_description' => $this->product_short_description,
            'seller' => new UserResource($this->whenLoaded('seller')),
            'status' => $this->status,
            'thumbnail' => $this->thumbnail,
            'price' => VariantPriceResource::collection($this->whenLoaded('variant_prices')),
            'variants' => VariantResource::collection($this->whenLoaded('variants')),
            'publish_status' => $this->publish_status,
        ];
    }
}
