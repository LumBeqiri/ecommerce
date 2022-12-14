<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Discount extends Model
{
    use HasFactory;
    use HasUuid;


    protected $fillable = [
        'name',
        'desc',
        'discount_percent',
        'active',
    ];

    public function product() : HasMany
    {
        return $this->hasMany(Product::class);
    }
}
