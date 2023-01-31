<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    use HasFactory;
    use HasUuid;


    protected $guarded = [];

    public function product() : HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function discount_rule() : BelongsTo
    {
        return $this->belongsTo(DiscountRule::class);
    }
}
