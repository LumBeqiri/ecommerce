<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\UserSettings */
class UserSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->ulid,
            'phone' => $this->phone,
            'city' => $this->city,
            'country' => $this->country,
            'zipcode' => $this->zip,
            'theme' => $this->theme,
        ];
    }
}
