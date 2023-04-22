<?php

namespace App\Http\Resources;

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
            'buyer' => new UserResource($this->whenLoaded('user')),
            'cart_items' => CartItemResource::collection($this->whenLoaded('cart_items')),
            'total' => $this->total_cart_price,
            'is_closed' => $this->is_closed,
        ];
    }
}
