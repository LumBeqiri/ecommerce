<?php

namespace App\Models;

use App\Models\Country;
use App\Traits\HasUuid;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function countries() : HasMany
    {
        return $this->hasMany(Country::class);
    }

    public function currency() : BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
