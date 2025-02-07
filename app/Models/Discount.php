<?php

namespace App\Models;

use App\Traits\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory;
    use HasUlids;

    protected $casts = [
        'starts_at' => 'datetime',
    ];

    protected $guarded = [];

    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the discount rule associated with the discount.
     *
     * @return BelongsTo<\App\Models\DiscountRule>
     */
    public function discount_rule(): BelongsTo
    {
        return $this->belongsTo(DiscountRule::class);
    }


    /**
     * Get the parent discount associated with the discount.
     *
     * @return BelongsTo<\App\Models\Discount>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    /** 
     * Get the regions associated with the discount.
     *
     * @return BelongsToMany<\App\Models\Region>
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class);
    }
}
