<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxProvider extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }
}
