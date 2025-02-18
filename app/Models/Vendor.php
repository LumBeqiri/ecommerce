<?php

namespace App\Models;

use App\Traits\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Vendor extends Model
{
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function payment_processors(): HasMany
    {
        return $this->hasMany(PaymentProcessor::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function ownsProduct(Product $product): bool
    {
        return $this->id === $product->vendor_id;
    }

    /**
     * @return HasMany<\App\Models\VendorOrder, $this>
     */
    public function vendor_orders(): HasMany
    {
        return $this->hasMany(VendorOrder::class);
    }

    public function order_items(): HasManyThrough
    {
        return $this->hasManyThrough(OrderItem::class, VendorOrder::class, 'vendor_id', 'vendor_order_id');
    }
    
}
