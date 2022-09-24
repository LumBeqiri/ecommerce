<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Attribute
 *
 * @property int $id
 * @property string $attribute_type
 * @property string $attribute_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Variant[] $variants
 * @property-read int|null $variants_count
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereAttributeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereAttributeValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereUuid($value)
 */
	class Attribute extends \Eloquent {}
}

namespace App\Models{
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
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Buyer whereUuid($value)
 */
	class Buyer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Cart
 *
 * @property int $id
 * @property int $order_id
 * @property int $total_cart_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereTotalCartPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUuid($value)
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CartItem
 *
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $count
 * @property int $total_item_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereTotalItemPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereUuid($value)
 */
	class CartItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $subcategory
 * @property-read int|null $subcategory_count
 * @method static \Database\Factories\CategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUuid($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $symbol
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Currency extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Discount
 *
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property int $discount_percent
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $product
 * @property-read int|null $product_count
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newQuery()
 * @method static \Illuminate\Database\Query\Builder|Discount onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereDiscountPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Discount withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Discount withoutTrashed()
 * @mixin \Eloquent
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereUuid($value)
 */
	class Discount extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Image
 *
 * @property int $id
 * @property string $image
 * @property int $imageable_id
 * @property string $imageable_type
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Model|\Eloquent $imageable
 * @method static \Database\Factories\ImageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Query\Builder|Image onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereImageableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereImageableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Image withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Image withoutTrashed()
 * @mixin \Eloquent
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUuid($value)
 */
	class Image extends \Eloquent {}
}

namespace App\Models{
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
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUuid($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $seller_id
 * @property int $currency_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $categories
 * @property-read int|null $categories_count
 * @property-read Currency $currency
 * @property-read \App\Models\Discount|null $discount
 * @property-read mixed $price
 * @property-read \Illuminate\Database\Eloquent\Collection|Image[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Seller $seller
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Variant[] $variants
 * @property-read int|null $variants_count
 * @method static \Database\Factories\ProductFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUuid($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
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
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereUuid($value)
 */
	class Seller extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin \Eloquent
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUuid($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Variant
 *
 * @property int $id
 * @property int $product_id
 * @property string $sku
 * @property string $variant_name
 * @property string $short_description
 * @property string $long_description
 * @property int $price
 * @property int $stock
 * @property string $status
 * @property int|null $discount_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read int|null $images_count
 * @property-read \App\Models\Product $product
 * @method static \Database\Factories\VariantFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereLongDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereVariantName($value)
 * @mixin \Eloquent
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereUuid($value)
 */
	class Variant extends \Eloquent {}
}

