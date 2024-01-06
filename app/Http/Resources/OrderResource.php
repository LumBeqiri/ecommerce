<?php

namespace App\Http\Resources;

use App\Http\Resources\BuyerResource;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
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
            'buyer' => new BuyerResource($this->buyer),
            'shipping_name' => $this->shipping_name,
            'shipping_address' => $this->shipping_address,
            'shipping_city' => $this->shipping_city,
            'shipping_country' => $this->shipping_country,
            'order_tax' => $this->order_tax,
            'order_date' => $this->order_date,
            'order_shipped' => $this->order_shipped,
            'order_email' => $this->order_email,
            'order_phone' => $this->order_phone,
            'payment' => 1,
            'total' => $this->total,
        ];
    }
}
