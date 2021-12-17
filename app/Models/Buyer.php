<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Scopes\BuyerScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buyer extends User
{
    use HasFactory, SoftDeletes;
    public $table = "users";
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }
}
