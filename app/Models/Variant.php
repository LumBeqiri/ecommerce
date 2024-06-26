<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model
{
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function medias(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class)->withTimestamps();
    }

    public function cart_item(): HasOne
    {
        return $this->hasOne(CartItem::class);
    }

    public function variant_prices(): HasMany
    {
        return $this->hasMany(VariantPrice::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->withTimestamps();
    }
}
