<?php
namespace App\Services;

use App\Models\Image;

class UploadProductService{

    public static function upload($newProduct, $images){
        
        //if there are images
        if($images){
            //for every image:
            foreach($images as $image){
                // take the path of the image and store the image in local storage
                $path = 'public/'. $image->store('img');
                //save the file to image property
                $imgData['image'] = $path;
                //attach image to the product
                $imgData['product_id'] = $newProduct->id;
                //title of the image is the same as in users' machine
                $imgData['title'] = $image->getClientOriginalName();
                // create the image and store the data in the database
                Image::create($imgData);
            }
        }
        return $newProduct;

    }
}