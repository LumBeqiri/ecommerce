<?php

namespace App\Models;

use App\Scopes\BuyerScope;
use App\Models\{User,Order};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buyer extends User
{
    use HasFactory;

    protected static function boot(){
        static::addGlobalScope(new BuyerScope);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
