<?php

namespace App\Http\Resources;

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
            'seller' => new UserResource($this->whenLoaded('vendor')->user),
            'status' => $this->status,
            'thumbnail' => $this->thumbnail,
            'price' => VariantPriceResource::collection($this->whenLoaded('variant_prices')),
            'variants' => VariantResource::collection($this->whenLoaded('variants')),
            // 'publish_status' => $this->publish_status,
        ];
    }
}
