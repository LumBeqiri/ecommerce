<?php

namespace App\Models;

use App\Traits\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use HasUlids;
    use InteractsWithMedia;
    use SoftDeletes;

    public const AVAILABLE_PRODUCT = 'available';

    public const UNAVAILABLE_PRODUCT = 'unavailable';

    public const PUBLISHED = 'published';

    public const DRAFT = 'draft';

    protected $hidden = ['pivot'];

    protected $guarded = [];

    /**
     * @return BelongsTo<\App\Models\Discount, $this>
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * @return BelongsToMany<\App\Models\Category, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * @return HasMany<\App\Models\Variant, $this>
     */
    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    /**
     * @return BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<\App\Models\Vendor, $this>
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('thumbnails')
            ->singleFile();
    }

    public function variant_prices(): HasManyThrough
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
