<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Order;
use App\Models\Category;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $seller_id
 * @property int $currency_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $categories
 * @property-read int|null $categories_count
 * @property-read Currency $currency
 * @property-read \App\Models\Discount|null $discount
 * @property-read mixed $price
 * @property-read \Illuminate\Database\Eloquent\Collection|Image[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Seller $seller
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Variant[] $variants
 * @property-read int|null $variants_count
 * @method static \Database\Factories\ProductFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory,SoftDeletes;

    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';

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

    public function currency() : BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function images() : MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getPriceAttribute($value){
        return $value/100;
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
