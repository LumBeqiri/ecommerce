<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Image;
use App\Models\Order;
use App\Models\Category;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';

    protected $hidden = ['pivot'];

    protected $fillable = [
        'name',
        'sku',
        'description',
        'seller_id',
        'currency_id',
    ];

    public function isAvaliable(){
        return $this->status == Product::AVAILABLE_PRODUCT;
    }

    // the price it's gonna sell for
    public function getSellingPrice(){
        if($this->discount !=null && $this->discount->active){
            $discount_percent = $this->discount->discount_percent;
            $price =  $this->price - $this->price*($discount_percent/100);
            return $price;
        }
        return $this->price;
    }

    //discount percentage per product
    public function getDiscountPercent(){
        if($this->discount){
            return $this->discount->discount_percent;
        }
        return 0;
        
    }

    public function discount(){
      return $this->belongsTo(Discount::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function variants(){
        return $this->hasMany(Variant::class);
    }
    
    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }

    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getPriceAttribute($value){
        return $value/100;
    }

    public function orders(){
        return $this->belongsToMany(Order::class, 'order_product')
        ->withTimestamps()
        ->withPivot([
            'quantity',
            'total'
        ]);
    }


    // public function orders(){
    //     return $this->belongsToMany(Order::class, 'order_product', 'product_id','order_id')
    //     ->withTimestamps()
    //     ->withPivot([
    //         'quantity',
    //         'total'
    //     ]);
    // }
    

}
