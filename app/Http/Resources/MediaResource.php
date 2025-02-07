<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Spatie\MediaLibrary\MediaCollections\Models\Media */
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
        /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $media */
        $media = $this->resource;

        return [
            'id' => $media->id,
            'name' => $media->name,
            'file_name' => $media->file_name,
            'mime_type' => $media->mime_type,
            'size' => $media->size,
            'url' => $media->getUrl(),
            'created_at' => $media->created_at,
            'updated_at' => $media->updated_at,
        ];
    }
}
