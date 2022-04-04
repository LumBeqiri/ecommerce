<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'image',
        'imageable_id',
        'imageable_type',
        'title'
    ];

    public function imageable(){
        return $this->morphTo();
    }
}
