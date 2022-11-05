<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'name',
        'code',
        'symbol',
    ];

    public function products() : HasMany
    {
        return $this->hasMany(Product::class);

    }
}
