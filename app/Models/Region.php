<?php

namespace App\Models;

use App\Traits\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = [];


    public function countries(): HasMany
    {
        return $this->hasMany(Country::class);
    }

    /**
     * Get the currency associated with the region.
     *
     * @return BelongsTo<\App\Models\Currency, self>
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    
    /**
     * Get the currency associated with the region.
     *
     * @return BelongsTo<\App\Models\TaxProvider, self>
     */
    public function tax_provider(): BelongsTo
    {
        return $this->belongsTo(TaxProvider::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * @return BelongsToMany<\App\Models\Discount, self>
     */
    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class);
    }
}
