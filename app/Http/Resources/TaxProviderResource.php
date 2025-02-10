<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\TaxProvider */
class TaxProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'tax_provider' => $this->tax_provider,
            'is_installed' => $this->is_installed,
        ];
    }
}
