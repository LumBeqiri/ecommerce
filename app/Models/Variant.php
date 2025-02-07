<?php

namespace App\Models;

use App\Traits\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Variant extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $guarded = [];
    /**
     * @return BelongsTo<\App\Models\Product, self>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsToMany<\App\Models\Attribute, self>
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class)->withTimestamps();
    }

    /**
     * @return HasOne<\App\Models\CartItem, self>
     */
    public function cart_item(): HasOne
    {
        return $this->hasOne(CartItem::class);
    }

    /**
     * @return HasMany<\App\Models\VariantPrice, self>
     */
    public function variant_prices(): HasMany
    {
        return $this->hasMany(VariantPrice::class);
    }

    /**
     * @return BelongsToMany<\App\Models\Order, self>
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withTimestamps();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('variants');
    }
}