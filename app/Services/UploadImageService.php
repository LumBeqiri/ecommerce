<?php
namespace App\Services;

use App\Contracts\UploadServiceContract;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class UploadImageService implements UploadServiceContract
{

    /**
     * @param mixed $newProductVariant
     * @param mixed $images
     * @param mixed $className
     * 
     * @return void
     */
    public static function upload($newProductVariant, $medias, $className){
     


        //if there are images
        if($medias){
            
            //for every image:
            foreach($medias as $media){
                $name = $media->hashName();
 
                $upload = $media->store('', 'images');
                //attach image to the product
                $mediaData['mediable_id'] = $newProductVariant->id;
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