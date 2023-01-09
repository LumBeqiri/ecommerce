<?php

namespace App\Models;

use App\Models\Region;
use App\Models\Variant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductPrices extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function currency() : BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }


    public function variant() : BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function region() : BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

}
