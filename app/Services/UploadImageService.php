<?php

namespace App\Services;

use App\Contracts\UploadServiceContract;
use App\Models\Media;
use Illuminate\Http\UploadedFile;

class UploadImageService implements UploadServiceContract
{
    /**
     * @param  mixed  $model
     * @param  array<int, UploadedFile>  $medias
     * @param  mixed  $className
     * @return void
     */
    public static function upload($model, $medias, $className)
    {
        if ($medias) {
            foreach ($medias as $media) {
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

                Media::updateOrCreate($mediaData);
            }
        }
    }
}
