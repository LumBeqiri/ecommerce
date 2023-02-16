<?php

namespace App\Models;

use App\Scopes\SellerScope;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends User
{
    use HasFactory, SoftDeletes;
    use HasUuid;

    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
