<?php

namespace App\Models;

use App\Traits\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = [];

    /**
     * @return HasMany<\App\Models\CartItem, $this>
     */
    public function cart_items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return BelongsTo<\App\Models\Buyer, $this> 
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }


    /**
     * @return BelongsTo<\App\Models\Region, $this>
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * @return BelongsTo<\App\Models\Payment, $this>
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function isEmpty(): bool
    {
        return $this->cart_items()->count() === 0;
    }
}
