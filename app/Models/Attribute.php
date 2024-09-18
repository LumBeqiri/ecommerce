<?php

namespace App\Models;

use App\Traits\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = [];

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class)->withTimestamps();
    }
}
