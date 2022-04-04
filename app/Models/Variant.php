<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'price',
        'short_desc',
        'long_desc',
        'product_id',
        'discount_id',
        'stock',
        'status'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }
}
