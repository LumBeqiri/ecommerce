<?php

namespace App\Models;

use App\Scopes\BuyerScope;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buyer extends User
{
    use HasFactory;
    public $table = "users";
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
