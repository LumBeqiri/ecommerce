<?php

namespace App\Models;

use App\Models\User;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cart extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];


    public function cart_items() : HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function buyer() : BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }
}
