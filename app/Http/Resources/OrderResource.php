<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
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
            'buyer' => new BuyerResource($this->buyer),
            'shipping_name' => $this->shipping_name,
            'shipping_address' => $this->shipping_address,
            'shipping_city' => $this->shipping_city,
            'shipping_country' => new CountryResource($this->whenLoaded('shipping_country')),
            'tax_rate' => $this->tax_rate,
            'tax_total' => $this->tax_total,
            'ordered_at' => $this->ordered_at,
            'shipped_at' => $this->shipped_at,
            'order_email' => $this->order_email,
            'order_phone' => $this->order_phone,
            'payment' => 1,
            'total' => $this->total,
        ];
    }
}
