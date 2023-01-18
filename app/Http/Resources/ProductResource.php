<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'product_name' => $this->product_name,
            'long_description' => $this->product_long_description,
            'product_short_description' => $this->product_short_description,
            'seller' => new UserResource($this->seller),
            'images' => $this->medias,
            'status' => $this->status,
            'thumbnail' => $this->thumbnail,
        ];
    }
}
