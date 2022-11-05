<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class CartItem extends Model
{
    use HasFactory;
    use HasUuid;

    public function cart() : BelongsTo
    {
        return $this->belongsTo(Cart::class); 
    }

    public function product() : HasOne
    {
        return $this->hasOne(Variant::class);
    }
}
