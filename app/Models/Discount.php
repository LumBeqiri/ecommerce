<?php

namespace App\Models;

use App\Models\Region;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discount extends Model
{
    use HasFactory;
    use HasUuid;

    protected $casts = [
        'starts_at' => 'datetime',
    ];

    protected $guarded = [];

    public function product() : HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function discount_rule() : BelongsTo
    {
        return $this->belongsTo(DiscountRule::class);
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function regions() : BelongsToMany 
    {
        return $this->belongsToMany(Region::class);
    }
}
