<?php

namespace App\Services;

use App\Contracts\UploadServiceContract;
use App\Models\Media;

class UploadImageService implements UploadServiceContract
{
    /**
     * @param  mixed  $model
     * @param  mixed  $images
     * @param  mixed  $className
     * @return App\Models\Media
     */
    public static function upload($model, $media, $className)
    {
        if ($media) {
            $name = $media->hashName();
            $upload = $media->store('', 'images');

            $mediaData['mediable_id'] = $model->id;
            $mediaData['mediable_type'] = $className;
            $mediaData['name'] = $name;
            $mediaData['file_name'] = $media->getClientOriginalName();
            $mediaData['mime_type'] = $media->getClientMimeType();
            $mediaData['path'] = $upload;
            $mediaData['disk'] = 'images';
            $mediaData['size'] = $media->getSize();
            $mediaData['collection'] = 'products';

            return Media::updateOrCreate($mediaData);
        }
    }
}
