<?php
namespace App\Services;

use App\Models\Image;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

class UploadPhotoService{
    use ApiResponser;

    public static function upload($data, $images){
        return DB::transaction(function() use ($images, $data){
            //first create a product
            $newProduct = Product::create($data);
            //retrieve all photos
            
            //for every photo:
            foreach($images as $image){
                // take the path of the photo and store the photo in local storage
                $path = 'public/'. $image->store('img');
                //save the file to image property
                $imgData['image'] = $path;
                //attach photo to the product
                $imgData['product_id'] = $newProduct->id;
                //title of the photo is the same as in users' machine
                $imgData['title'] = $image->getClientOriginalName();
                // create the image and store the data in the database
                Image::create($imgData);
            }
            
            return $newProduct;
        });
    }
}