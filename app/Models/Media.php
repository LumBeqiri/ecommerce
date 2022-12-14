<?php

namespace App\Models;


use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Media extends Model
{
    use HasFactory;
    use HasUuid;

    protected $table = 'medias';
    protected $guarded = [];

    public function mediable() : MorphTo
    {
        return $this->morphTo();
    }
}
