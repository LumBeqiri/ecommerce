<?php

namespace App\Models;

use App\Traits\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorOrder extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'vendor_orders';
    protected $guarded = [];


    /**
     * @return BelongsTo<\App\Models\Vendor, $this>
     */
    public function vendor() : BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * @return BelongsTo<\App\Models\Order, $this>
     */
    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
