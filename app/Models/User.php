<?php

namespace App\Models;

use App\Traits\HasUlids;
use App\values\Roles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;
    use HasUlids;

    public const VERIFIED_USER = '1';

    public const UNVERIFIED_USER = '0';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'city',
        'country_id',
        'user_id',
        'password',
        'verified',
        'phone',
        'verification_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isVerified(): bool
    {
        return $this->verified === User::VERIFIED_USER;
    }

    public static function generateVerificationCode(): string
    {
        return Str::random(40);
    }

    public function user_settings(): HasOne
    {
        return $this->hasOne(UserSettings::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function customerGroups(): BelongsToMany
    {
        return $this->belongsToMany(CustomerGroup::class);
    }

    public function customerGroup(): HasMany
    {
        return $this->hasMany(CustomerGroup::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function buyer(): HasOne
    {
        return $this->hasOne(Buyer::class);
    }

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function getRoelName(): string
    {
        return $this->getRoleNames()->first();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Roles::ADMIN);
    }

    public function isStaff(): bool
    {
        return $this->hasRole(Roles::STAFF);
    }

    public function isVendor(): bool
    {
        return $this->hasRole(Roles::VENDOR);
    }

    public function isBuyer(): bool
    {
        return $this->hasRole(Roles::BUYER);

    }
}
