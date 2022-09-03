<?php
namespace App\Services;

use App\Models\Image;

class UploadImageService{

    /**
     * @param mixed $newProductVariant
     * @param mixed $images
     * @param mixed $className
     * 
     * @return void
     */
    public static function upload($newProductVariant, $images, $className){
     
        
        //if there are images
        if($images){
            //for every image:
            foreach($images as $image){
 
                $path = 'public/'. $image->store('img');
                $imgData['image'] = $path;
                //attach image to the product
                $imgData['imageable_id'] = $newProductVariant->id;
                $imgData['imageable_type'] = $className;
                $imgData['title'] = $image->getClientOriginalName();

                Image::create($imgData);
            }
        }

    }
}