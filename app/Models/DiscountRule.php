<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DiscountRule extends Model
{
    use HasFactory;


    protected $fillable = [];
    

    public function discount() : HasOne 
    {
        return $this->hasOne(Discount::class);
    }
}
