<?php

namespace App\Models;

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

    protected $fillable = [
        'name',
        'sku',
        'price',
        'weight',
        'size',
        'color',
        'short_desc',
        'long_desc',
        'seller_id',
        'currency_id',
        'stock',
        'status'
    ];

    public function isAvaliable(){
        return $this->status == Product::AVAILABLE_PRODUCT;
    }

    public function categories(){
        return $this->belongsToMany(Category::class)->withTimestamps();;
    }

    
    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }

    public function images(){
        return $this->hasMany(Image::class);
    }

    public function getPriceAttribute($value){
        return $value;
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
