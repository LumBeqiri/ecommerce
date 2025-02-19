<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Mail\OrderReceipt;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\VendorOrder;
use App\values\OrderStatusTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends ApiController
{
    protected User $user;

    public function __construct()
    {
        /*** @var $user User*/
        $this->user = auth()->user();
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $region_id = $this->user->region_id;

        $cart = Cart::with([
            'cart_items.variant.variant_prices' => function ($query) use ($region_id) {
                $query->where('region_id', $region_id);
            },
            'cart_items.variant.product.vendor',
            'region:id,currency_id',
            'region.currency:id,name',
        ])
            ->where('buyer_id', $this->user->buyer->id)
            ->first();

        DB::beginTransaction();
        try {
            $order_data = $request->validated();

            if ($request->input('different_shipping_address')) {
                $order_data['shipping_name'] = $request->input('shipping_name');
                $order_data['shipping_city'] = $request->input('shipping_city');
                $order_data['shipping_country_id'] = $request->input('shipping_country_id');
                $order_data['shipping_address'] = $request->input('shipping_address');
            } else {
                $order_data['shipping_name'] = $this->user->name;
                $order_data['shipping_city'] = $this->user->user_settings->city;
                $order_data['shipping_country_id'] = $this->user->user_settings->country->id;
                $order_data['shipping_address'] = $this->user->buyer->shipping_address;
            }

            unset($order_data['different_shipping_address']);

            $order_data['buyer_id'] = $this->user->buyer->id;
            $order_data['total'] = $cart->total_cart_price;
            $order_data['ordered_at'] = $request->input('ordered_at', now());
            $order_data['payment_id'] = 1;
            $order_data['currency_id'] = $cart->region->currency->id;
            $order_data['tax_rate'] = $request->input('tax_rate');
            $order_data['tax_total'] = 0;
            $order_data['order_email'] = $request->input('order_email');
            $order_data['order_phone'] = $request->input('order_phone');
            $order_data['status'] = OrderStatusTypes::PENDING->value;

            $order = Order::create($order_data);

            // Group cart items by vendor
            $vendorItems = $cart->cart_items->groupBy('variant.product.vendor_id');

            // Create vendor orders
            foreach ($vendorItems as $vendorId => $items) {
                $vendorTotal = $items->sum(function ($item) {
                    return $item->price * $item->quantity;
                });

                VendorOrder::create([
                    'ulid' => Str::ulid(),
                    'vendor_id' => $vendorId,
                    'order_id' => $order->id,
                    'tax_rate' => $request->input('tax_rate'),
                    'tax_total' => 0, // Calculate if needed
                    'total' => $vendorTotal,
                    'currency_id' => $cart->region->currency->id,
                    'status' => OrderStatusTypes::PENDING->value,
                ]);

                // Create order items for this vendor
                foreach ($items as $item) {
                    $variant = $item->variant;
                    $variant_price = $variant->variant_prices->firstWhere('region_id', $region_id);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'variant_id' => $variant->id,
                        'price' => $variant_price->price,
                        'quantity' => $item->quantity,
                    ]);
                }
            }

            DB::commit();

            Mail::to($this->user)->send(new OrderReceipt($order));

            return $this->showOne(
                new OrderResource($order->load(['order_items', 'vendor_orders']))
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
