<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Discount */
class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'ulid' => $this->ulid,
            'code' => $this->code,
            'is_dynamic' => $this->is_dynamic,
            'is_disabled' => $this->is_disabled,
            'discount_rule' => new DiscountRuleResource($this->whenLoaded('discount_rule')),
            'parent_id' => new DiscountResource($this->whenLoaded('parent')),
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'usage_limit' => $this->usage_limit,
            'usage_count' => $this->usage_count,
            'created_at' => $this->created_at,
        ];
    }
}
