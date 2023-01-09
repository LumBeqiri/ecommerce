<?php

namespace App\Http\Resources;

use App\Services\PriceService;
use App\Http\Resources\ProductPriceResource;
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
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'stock' => $this->stock,
            'status' => $this->status,
            'price' => ProductPriceResource::collection($this->whenLoaded('product_prices')),
        ];
    }
}
