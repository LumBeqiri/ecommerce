<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;
    use HasUuid;

    public const AVAILABLE_PRODUCT = 'available';

    public const UNAVAILABLE_PRODUCT = 'unavailable';

    public const PUBLISHED = 'published';

    public const DRAFT = 'draft';

    protected $hidden = ['pivot'];

    protected $guarded = [];

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function discount_rules(): BelongsToMany
    {
        return $this->belongsToMany(DiscountRule::class);
    }

    public function discount_conditions(): BelongsToMany
    {
        return $this->belongsToMany(DiscountCondition::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medias(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function variant_prices()
    {
        return $this->hasManyThrough(
            VariantPrice::class, // The target model
            Variant::class, // The intermediate model
            'product_id', // The foreign key on the intermediate model
            'variant_id', // The foreign key on the target model
            'id', // The local key on the source model
            'id' // The local key on the intermediate model
        );
    }
}
