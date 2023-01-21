<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            "uuid" => $this->uuid,
            "mediable_id" => $this->mediable_id,
            "mediable_type" => $this->mediable_type,
            "name" => $this->name,
            "file_name" => $this->file_name,
            "mime_type" => $this->mime_type,
            "path" => $this->path,
            "disk" => $this->disk,
            "collection" => $this->collection,
            "size" => $this->size
            ];
    }
}
