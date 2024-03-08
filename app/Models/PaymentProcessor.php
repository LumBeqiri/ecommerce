<?php

namespace App\Models;

use App\Models\Vendor;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentProcessor extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function vendor() : BelongsTo
    {
        return $this->belongsTo(Vendor::class);    
    }

}
