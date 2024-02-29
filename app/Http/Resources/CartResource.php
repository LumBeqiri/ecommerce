<?php

namespace App\Http\Resources;

use Brick\Money\Money;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Cart */
class CartResource extends JsonResource
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
            'buyer' => new BuyerResource($this->whenLoaded('buyer')),
            'cart_items' => CartItemResource::collection($this->whenLoaded('cart_items')),
            'total' => Money::ofMinor($this->total_cart_price,$this->region->currency->code),
            'is_closed' => $this->is_closed,
        ];
    }
}
