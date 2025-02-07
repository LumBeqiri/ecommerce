<?php

namespace App\Models;

use App\Traits\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buyer extends User
{
    use HasFactory, SoftDeletes;
    use HasUlids;

    protected $table = 'buyers';

    protected $guarded = [];

    /**
     * @return HasMany<\App\Models\Cart, self>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasMany<\App\Models\Cart, self>
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * @return BelongsTo<\App\Models\User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<\App\Models\Country, self>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
