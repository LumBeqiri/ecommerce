<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Spatie\Permission\Models\Permission */
class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, int|string|null>
     */
    public function toArray(Request $request): array
    {
        /** @var array<string, int|string|null> */
        return parent::toArray($request);
    }
}
