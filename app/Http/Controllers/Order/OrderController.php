<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;

class OrderController extends ApiController
{
    public function index(): JsonResponse
    {
        $orders = Order::all();

        return $this->showAll(OrderResource::collection($orders));
    }

    public function show(Order $order): JsonResponse
    {
        return $this->showOne(new OrderResource($order));
    }

    public function store(StoreOrderRequest $request)
    {
        $region_id = auth()->user()->country->region->id;

        $cart = Cart::with(['cart_items.variant.variant_prices' => function ($query) use ($region_id) {
            $query->where('region_id', $region_id);
        }, 'region:id,currency_id', 'region.currency:id,name'])
        ->where('user_id', auth()->id())
        ->first();

        $order_data = $request->validated();
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

        return $this->showOne($order->load('order_items'));
    }
}
