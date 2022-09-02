<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Scopes\BuyerScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Buyer
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $city
 * @property string|null $state
 * @property int|null $zip
 * @property string|null $shipping_address
 * @property string|null $phone
 * @property string|null $remember_token
 * @property string $verified
 * @property string|null $verification_token
 * @property string $admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Cart[] $carts
 * @property-read int|null $carts_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer newQuery()
 * @method static \Illuminate\Database\Query\Builder|Buyer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|Buyer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Buyer withoutTrashed()
 * @mixin \Eloquent
 */
class Buyer extends User
{
    use HasFactory, SoftDeletes;
    public $table = "users";
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function carts() : HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
