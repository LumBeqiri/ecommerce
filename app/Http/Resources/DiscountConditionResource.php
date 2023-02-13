<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->uuid,
            'model_type' => $this->model_type,
            'operator' => $this->operator,
            'metadata' => $this->metadata,
            'products' => ProductResource::collection($this->whenLoaded('products'))
        ];
    }
}
