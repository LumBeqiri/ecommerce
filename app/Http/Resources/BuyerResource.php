<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class BuyerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->ulid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'city' => $this->city,
            'country' => new CountryResource($this->whenLoaded('country')),
            'zip' => $this->zip,
            'shipping_address' => $this->shipping_address,
            'phone' => $this->phone,
            'role' => $this->user->getRoleNames()->first(),
        ];
    }
}
