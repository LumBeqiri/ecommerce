<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\HasUuid;
use App\Models\DiscountRule;
use App\Models\CustomerGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DiscountCondition extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function discount_rule() : BelongsTo 
    {
        return $this->belongsTo(DiscountRule::class);
    }

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function customer_groups() : BelongsToMany
    {
        return $this->belongsToMany(CustomerGroup::class);
    }

}
