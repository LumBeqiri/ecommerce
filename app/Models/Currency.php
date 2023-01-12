<?php

namespace App\Models;

use App\Models\Region;
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

    public function regions() : HasMany
    {
        return $this->hasMany(Region::class);
    }
}
