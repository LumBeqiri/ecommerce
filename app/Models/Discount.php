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

    public function discount_rule(): BelongsTo
    {
        return $this->belongsTo(DiscountRule::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class);
    }
}
