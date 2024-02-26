<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariantPrice extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function currency() : BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
