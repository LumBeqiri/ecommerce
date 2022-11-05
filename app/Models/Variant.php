<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Variant extends Model
{
    use HasFactory;
    use HasUuid;


    protected $guarded = [];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function medias() : MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function attributes() : BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function cart_item() : BelongsTo
    {
        return $this->belongsTo(CartItem::class);
    }
}
