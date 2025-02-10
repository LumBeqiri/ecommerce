<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CartItem */
class CartItemResource extends JsonResource
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
            'cart' => $this->cart->ulid,
            'variant' => new VariantResource($this->variant),
            'quantity' => $this->quantity,
        ];
    }
}
