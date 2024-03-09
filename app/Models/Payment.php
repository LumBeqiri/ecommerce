<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }
}
