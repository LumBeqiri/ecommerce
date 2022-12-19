<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Category extends Model
{
    use HasFactory;
    use HasUuid;


    protected $hidden = ['pivot'];

    protected $fillable = [
        'name',
        'description',
        'slug',
        'parent_id'
    ];

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class)
        ->withTimestamps();
    }

    public function subcategory() : HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
