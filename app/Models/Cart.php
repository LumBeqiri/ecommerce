<?php

namespace App\Models;

use App\Models\Buyer;
use App\Models\Region;
use App\Traits\HasUuid;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function cart_items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
