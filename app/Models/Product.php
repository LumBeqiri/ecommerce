<?php

namespace App\Models;

use App\Models\Order;
use App\Traits\HasUuid;
use App\Models\Category;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    use HasUuid;


    public const AVAILABLE_PRODUCT = 'available';
    public const UNAVAILABLE_PRODUCT = 'unavailable';

    protected $hidden = ['pivot'];

    protected $fillable = [
        'name',
        'description',
        'seller_id',
        'currency_id',
    ];

    // move isAvailable to variant
    // public function isAvaliable() 
    // {
    //     return $this->status == Product::AVAILABLE_PRODUCT;
    // }


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

    public function discount() : BelongsTo
    {
      return $this->belongsTo(Discount::class);
    }

    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function variants() : HasMany
    {
        return $this->hasMany(Variant::class);
    }
    
    public function seller() : BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency() : BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function medias() : MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function orders() : BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product')
        ->withTimestamps();
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
