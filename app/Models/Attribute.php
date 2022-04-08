<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_type',
        'attribute_value'
    ];

    public function variants(){
        return $this->belongsToMany(Variant::class);
    }
}
