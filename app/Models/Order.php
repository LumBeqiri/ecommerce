<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Buyer;
use App\Models\Seller;
use App\Models\Product;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $buyer_id
 * @property string $ship_name
 * @property string $ship_address
 * @property string $ship_city
 * @property string $ship_state
 * @property float $order_tax
 * @property float $total
 * @property string $order_date
 * @property string $order_shipped
 * @property string $order_email
 * @property string $order_phone
 * @property int $payment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Buyer $buyer
 * @property-read Cart|null $cart
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\OrderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Query\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderShipped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Order withoutTrashed()
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;
    use HasUuid;


    const SHIPPED_ORDER = 'true';
    const UNSHIPPED_ORDER = 'false';
    

    protected $fillable =[
        'buyer_id',
        'product_id',
        'quantity',
        'ship_name',
        'ship_address',
        'ship_city',
        'ship_state',
        'order_tax',
        'order_date',
        'total',
        'order_shipped',
        'order_email',
        'order_phone',
        'payment_id'
    ];

    public function buyer() : BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product','order_id', 'product_id')
        ->withTimestamps();
    }

    public function cart() : HasOne
    {
        return $this->hasOne(Cart::class);
    }

}
