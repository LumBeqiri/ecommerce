<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Scopes\SellerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Seller
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
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Seller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller newQuery()
 * @method static \Illuminate\Database\Query\Builder|Seller onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller query()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|Seller withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Seller withoutTrashed()
 * @mixin \Eloquent
 */
class Seller extends User
{
    use HasFactory, SoftDeletes;
    public $table = "users";

    protected static function boot(){
 
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }

    public function products() : HasMany
    {
        return $this->hasMany(Product::class);
    }
}
