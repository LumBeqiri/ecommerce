<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    use HasUuid;

    public const SHIPPED_ORDER = 'true';

    public const UNSHIPPED_ORDER = 'false';

    protected $guarded = [];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class)
        ->withTimestamps();
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }
}
