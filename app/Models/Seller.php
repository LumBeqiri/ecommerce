<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Scopes\SellerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends User
{
    use HasFactory, SoftDeletes;
    public $table = "users";

    protected static function boot(){
 
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
