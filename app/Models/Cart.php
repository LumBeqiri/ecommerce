<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty',
        'total_price',
        'user_id'

    ];

    public function buyer(){
        return $this->belongsTo(Buyer::class);
    }

    public function product(){
        return $this->hasMany(Product::class);
    }

}
