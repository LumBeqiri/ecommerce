<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountRuleResource extends JsonResource
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
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'value' => $this->value,
            'allocation' => $this->allocation,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'discount_conditions' => DiscountConditionResource::collection($this->whenLoaded('discount_conditions')),
        ];
    }
}
