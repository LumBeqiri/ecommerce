<?php

namespace App\Http\Resources;

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
            'buyer' => new UserResource($this->buyer),
            'ship_name' => $this->ship_name,
            'ship_address' => $this->ship_address,
            'ship_city' => $this->ship_city,
            'ship_state' => $this->ship_state,
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
