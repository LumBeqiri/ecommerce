<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomerGroup extends Model
{
    use HasFactory;
    use HasUuid;

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $guarded = [];

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    //belongs to a seller/store
    public function user() : BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
