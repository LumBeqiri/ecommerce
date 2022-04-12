<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'variant_name',
        'price',
        'short_description',
        'long_description',
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

    public function attributes(){
        return $this->belongsToMany(Attribute::class);
    }
}
