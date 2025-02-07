<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string $attribute_type
 * @property string $attribute_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Variant> $variants
 * @property-read int|null $variants_count
 * @method static \Database\Factories\AttributeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute whereAttributeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute whereAttributeValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute whereUpdatedAt($value)
 */
	class Attribute extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string|null $shipping_address
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Buyer|null $buyer
 * @property-read \App\Models\Cart|null $cart
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart> $carts
 * @property-read int|null $carts_count
 * @property-read \App\Models\Country|null $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerGroup> $customerGroup
 * @property-read int|null $customer_group_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerGroup> $customerGroups
 * @property-read int|null $customer_groups_count
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Region|null $region
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Staff|null $staff
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\UserSettings|null $user_settings
 * @property-read \App\Models\Vendor|null $vendor
 * @method static \Database\Factories\BuyerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buyer withoutTrashed()
 */
	class Buyer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property int $buyer_id
 * @property int|null $total_cart_price
 * @property int $is_closed
 * @property int $has_been_discounted
 * @property int|null $payment_id
 * @property int|null $vendor_id
 * @property int $region_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Buyer $buyer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem> $cart_items
 * @property-read int|null $cart_items_count
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\Payment|null $payment
 * @property-read \App\Models\Region $region
 * @method static \Database\Factories\CartFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereHasBeenDiscounted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereIsClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereTotalCartPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereVendorId($value)
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property int $cart_id
 * @property int $variant_id
 * @property int $quantity
 * @property int $variant_price_id
 * @property int|null $discounted_price
 * @property int|null $currency_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cart $cart
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\Variant $variant
 * @method static \Database\Factories\CartItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereDiscountedPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereVariantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereVariantPriceId($value)
 */
	class CartItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $subcategory
 * @property-read int|null $subcategory_count
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int|null $region_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Buyer> $buyers
 * @property-read int|null $buyers_count
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\Region|null $region
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\CountryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedAt($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $symbol
 * @property int|null $has_cents
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Region> $regions
 * @property-read int|null $regions_count
 * @method static \Database\Factories\CurrencyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereHasCents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereUpdatedAt($value)
 */
	class Currency extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string $name
 * @property array<array-key, mixed>|null $metadata
 * @property int $buyer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\CustomerGroupFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerGroup whereUpdatedAt($value)
 */
	class CustomerGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property int|null $vendor_id
 * @property string $code
 * @property int|null $is_dynamic
 * @property int|null $is_disabled
 * @property int $discount_rule_id
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon $starts_at
 * @property string|null $ends_at
 * @property int|null $usage_limit
 * @property int|null $usage_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DiscountRule $discount_rule
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read Discount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $product
 * @property-read int|null $product_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Region> $regions
 * @property-read int|null $regions_count
 * @method static \Database\Factories\DiscountFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereDiscountRuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereIsDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereIsDynamic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUsageCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUsageLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereVendorId($value)
 */
	class Discount extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string $description
 * @property int $region_id
 * @property string $discount_type
 * @property string $operator
 * @property float $value
 * @property string|null $allocation
 * @property string|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Discount|null $discount
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Region $region
 * @method static \Database\Factories\DiscountRuleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule whereAllocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule whereOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountRule whereValue($value)
 */
	class DiscountRule extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property int $buyer_id
 * @property string|null $shipping_name
 * @property string $shipping_address
 * @property string $shipping_city
 * @property string $shipping_country
 * @property float|null $order_tax
 * @property float $total
 * @property int $currency_id
 * @property string $order_date
 * @property string $order_shipped
 * @property string $order_email
 * @property string $order_phone
 * @property int|null $payment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Buyer $buyer
 * @property-read \App\Models\Cart|null $cart
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $order_items
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Variant> $variants
 * @property-read int|null $variants_count
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderShipped($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $variant_id
 * @property int $price
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\Order $order
 * @method static \Database\Factories\OrderItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereVariantId($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property int $vendor_id
 * @property int $amount
 * @property int $currency_id
 * @property int $payment_processor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cart|null $cart
 * @property-read \App\Models\TFactory|null $use_factory
 * @method static \Database\Factories\PaymentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentProcessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereVendorId($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string $name
 * @property int $vendor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\Vendor $vendor
 * @method static \Database\Factories\PaymentProcessorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProcessor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProcessor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProcessor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProcessor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProcessor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProcessor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProcessor whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProcessor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProcessor whereVendorId($value)
 */
	class PaymentProcessor extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string $product_name
 * @property int $vendor_id
 * @property string $status
 * @property string $publish_status
 * @property int $discountable
 * @property int|null $origin_country_id
 * @property int|null $discount_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\Discount|null $discount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DiscountRule> $discount_rules
 * @property-read int|null $discount_rules_count
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VariantPrice> $variant_prices
 * @property-read int|null $variant_prices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Variant> $variants
 * @property-read int|null $variants_count
 * @property-read \App\Models\Vendor $vendor
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDiscountable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOriginCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePublishStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereVendorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withoutTrashed()
 */
	class Product extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string $title
 * @property int $currency_id
 * @property int|null $tax_rate
 * @property string|null $tax_code
 * @property int|null $tax_provider_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart> $carts
 * @property-read int|null $carts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Country> $countries
 * @property-read int|null $countries_count
 * @property-read \App\Models\Currency $currency
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Discount> $discounts
 * @property-read int|null $discounts_count
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\TaxProvider|null $tax_provider
 * @method static \Database\Factories\RegionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereTaxCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereTaxProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereUpdatedAt($value)
 */
	class Region extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property int $user_id
 * @property string $position
 * @property string|null $status
 * @property string|null $notes
 * @property string|null $address
 * @property int $vendor_id
 * @property string|null $start_date
 * @property string|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Vendor $vendor
 * @method static \Database\Factories\StaffFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereVendorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff withoutTrashed()
 */
	class Staff extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string $tax_provider
 * @property int|null $is_installed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Region> $regions
 * @property-read int|null $regions_count
 * @method static \Database\Factories\TaxProviderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxProvider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxProvider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxProvider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxProvider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxProvider whereIsInstalled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxProvider whereTaxProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxProvider whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxProvider whereUpdatedAt($value)
 */
	class TaxProvider extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property int $verified
 * @property string|null $verification_token
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $region_id
 * @property-read \App\Models\Buyer|null $buyer
 * @property-read \App\Models\Cart|null $cart
 * @property-read \App\Models\Country|null $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerGroup> $customerGroup
 * @property-read int|null $customer_group_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerGroup> $customerGroups
 * @property-read int|null $customer_groups_count
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Region|null $region
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Staff|null $staff
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\UserSettings|null $user_settings
 * @property-read \App\Models\Vendor|null $vendor
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property string|null $city
 * @property int $country_id
 * @property string|null $zip
 * @property int $user_id
 * @property string $theme
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSettings whereZip($value)
 */
	class UserSettings extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property int $product_id
 * @property string $variant_name
 * @property string|null $variant_short_description
 * @property string|null $variant_long_description
 * @property int $stock
 * @property int|null $manage_inventory
 * @property string $status
 * @property string $publish_status
 * @property string|null $sku
 * @property string|null $barcode
 * @property string|null $ean
 * @property string|null $upc
 * @property int|null $allow_backorder
 * @property string|null $material
 * @property int|null $weight
 * @property string|null $weight_unit
 * @property int|null $length
 * @property string|null $length_unit
 * @property int|null $height
 * @property string|null $height_unit
 * @property int|null $width
 * @property string|null $width_unit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attribute> $attributes
 * @property-read int|null $attributes_count
 * @property-read \App\Models\CartItem|null $cart_item
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VariantPrice> $variant_prices
 * @property-read int|null $variant_prices_count
 * @method static \Database\Factories\VariantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereAllowBackorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereEan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereHeightUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereLengthUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereManageInventory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant wherePublishStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereUpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereVariantLongDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereVariantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereVariantShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereWeightUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereWidthUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant withoutTrashed()
 */
	class Variant extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property int $price
 * @property int $variant_id
 * @property int $region_id
 * @property int|null $currency_id
 * @property int|null $min_quantity
 * @property int|null $max_quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Currency|null $currency
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\Region $region
 * @property-read \App\Models\Variant $variant
 * @method static \Database\Factories\VariantPriceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice whereMaxQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice whereMinQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice whereVariantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantPrice withoutTrashed()
 */
	class VariantPrice extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ulid
 * @property string $vendor_name
 * @property string $city
 * @property int $country_id
 * @property int $user_id
 * @property int $status
 * @property string|null $approval_date
 * @property string|null $website
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentProcessor> $payment_processors
 * @property-read int|null $payment_processors_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Staff> $staff
 * @property-read int|null $staff_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\VendorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereApprovalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereVendorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor withoutTrashed()
 */
	class Vendor extends \Eloquent {}
}

