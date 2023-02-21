<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\DiscountCondition */
class DiscountConditionResource extends JsonResource
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
            /** @phpstan-ignore-next-line */
            'id' => $this->uuid,
            'model_type' => $this->model_type,
            'operator' => $this->operator,
            'metadata' => $this->metadata,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'customer_groups' => CustomerGroupResource::collection($this->whenLoaded('customer_groups')),
        ];
    }
}
