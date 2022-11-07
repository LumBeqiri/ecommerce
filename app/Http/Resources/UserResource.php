<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
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
            'id' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'shipping_address' => $this->shipping_address,
            'phone' => $this->phone,
        ];
    }
}
