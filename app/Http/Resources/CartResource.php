<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'buyer' => new UserResource($this->user),
            'cart_items' => CartItemResource::collection($this->whenLoaded('cart_items')),
            'total' => $this->total_cart_price,
        ];
    }
}
