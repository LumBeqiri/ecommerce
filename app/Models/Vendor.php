<?php

namespace App\Models;

use App\Models\User;
use App\Models\Staff;
use App\Traits\HasUuid;
use App\Models\PaymentProcessor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function payment_processors() : HasMany
    {
        return $this->hasMany(PaymentProcessor::class);
    }
}
