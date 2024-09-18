<?php

namespace App\Models;

use App\Traits\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = [];

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }
}
