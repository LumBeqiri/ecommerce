<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Variant
 *
 * @property int $id
 * @property int $product_id
 * @property string $sku
 * @property string $variant_name
 * @property string $short_description
 * @property string $long_description
 * @property int $price
 * @property int $stock
 * @property string $status
 * @property int|null $discount_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read int|null $images_count
 * @property-read \App\Models\Product $product
 * @method static \Database\Factories\VariantFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereLongDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereVariantName($value)
 * @mixin \Eloquent
 */
class Variant extends Model
{
    use HasFactory;
    use HasUuid;


    protected $fillable = [
        'sku',
        'variant_name',
        'price',
        'short_description',
        'long_description',
        'product_id',
        'discount_id',
        'stock',
        'status'
    ];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function images() : MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function attributes() : BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }
}
