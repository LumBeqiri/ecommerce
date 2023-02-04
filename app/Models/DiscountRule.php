<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DiscountRule extends Model
{
    use HasFactory;
    use HasUuid;


    protected $guarded = [];
    

    public function discount() : HasOne 
    {
        return $this->hasOne(Discount::class);
    }

    public function discount_condition() : HasMany
    {
        return $this->hasMany(DiscountCondition::class);
    }

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
