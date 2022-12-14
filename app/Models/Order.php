<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Buyer;
use App\Models\Product;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Order extends Model
{
    use HasFactory, SoftDeletes;
    use HasUuid;


    public const SHIPPED_ORDER = 'true';
    public const UNSHIPPED_ORDER = 'false';
    

    protected $fillable =[
        'buyer_id',
        'product_id',
        'quantity',
        'ship_name',
        'ship_address',
        'ship_city',
        'ship_state',
        'order_tax',
        'order_date',
        'total',
        'order_shipped',
        'order_email',
        'order_phone',
        'payment_id'
    ];

    public function buyer() : BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product','order_id', 'product_id')
        ->withTimestamps();
    }

    public function cart() : HasOne
    {
        return $this->hasOne(Cart::class);
    }

}
