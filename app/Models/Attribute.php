<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class)->withTimestamps();
    }
}
