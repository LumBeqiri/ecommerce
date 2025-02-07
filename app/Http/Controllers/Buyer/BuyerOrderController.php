<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Mail\OrderReceipt;
use App\Models\Buyer;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class BuyerOrderController extends ApiController
{
    protected User $user;

    public function __construct()
    {
        /*** @var $user User*/
        $this->user = auth()->user();
    }

    public function index(Buyer $buyer): JsonResponse
    {
        $orders = $buyer->orders;

        return $this->showAll(OrderResource::collection($orders));
    }

    public function store(StoreOrderRequest $request)
    {
        $region_id = $this->user->region_id;

        $cart = Cart::with(['cart_items.variant.variant_prices' => function ($query) use ($region_id) {
            $query->where('region_id', $region_id);
        }, 'region:id,currency_id', 'region.currency:id,name'])
            ->where('buyer_id', auth()->id())
            ->first();

        $order_data = $request->validated();
        if ($request->input('different_shipping_address')) {
            $order_data['shipping_name'] = $request->shipping_name;
            $order_data['shipping_city'] = $request->shipping_city;
            $order_data['shipping_country'] = $request->shipping_country;
            $order_data['shipping_address'] = $request->shipping_address;
        } else {
            $order_data['shipping_city'] = $this->user->user_settings->city;
            $order_data['shipping_country'] = $this->user->user_settings->country->name;
            // TODO: shipping address needs to be moved to user settings
            // $order_data['shipping_address'] = $this->user->user_settings->shipping_address;
        }

        unset($order_data['different_shipping_address']);

        $order_data['buyer_id'] = auth()->id();
        $order_data['total'] = $cart->total_cart_price;
        $order_data['order_date'] = now();
        $order_data['payment_id'] = 1;
        $order_data['currency_id'] = $cart->region->currency->id;

        $order = Order::create($order_data);

        foreach ($cart->cart_items as $item) {
            $variant = $item->variant;
            $variant_price = $variant->variant_prices->firstWhere('region_id', $region_id);
            OrderItem::create([
                'order_id' => $order->id,
                'variant_id' => $item->variant->id,
                'price' => $variant_price->price,
                'quantity' => $item->quantity,
            ]);
        }

        Mail::to(auth()->user())->send(new OrderReceipt($order));
        // return (new OrderReceipt($order))->render();

        return $this->showOne(
            new OrderResource($order->load('order_items'))
        );
    }
}
