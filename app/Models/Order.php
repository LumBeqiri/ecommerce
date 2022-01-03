<?php

namespace App\Models;

use App\Models\Buyer;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    const SHIPPED_ORDER = 'true';
    const UNSHIPPED_ORDER = 'false';
    

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

    public function buyer(){
        return $this->belongsTo(Buyer::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'order_product','order_id', 'product_id')
        ->withTimestamps()
        ->withPivot([
            'quantity',
        ]);
    }

}
