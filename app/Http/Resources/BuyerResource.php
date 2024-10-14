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
            'shipping_address' => $this->shipping_address,
            'role' => $this->user->getRoleNames()->first(),
            'user_settings' => new UserSettingsResource($this->user->user_settings),
        ];
    }
}
