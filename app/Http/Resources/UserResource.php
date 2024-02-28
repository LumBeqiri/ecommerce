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
            'email' => $this->email,
            'role' => $this->getRoleNames()->first(),
            'region' => new RegionResource($this->region),
            'permissions' => PermissionResource::collection($this->permissions),
        ];
    }
}
