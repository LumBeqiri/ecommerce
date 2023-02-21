<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CartItemRequest;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Models\Variant;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;

class BuyerCartController extends ApiController
{

    public function index(User $buyer) : JsonResponse
    {
        $cart = $buyer->cart()->with('cart_items')->first();

        return $this->showOne(new CartResource($cart));
    }


    public function store(CartRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $items = $data['items'];

        $cart = Cart::updateOrCreate(['user_id' => auth()->id()]);

        CartService::moveItemsToDB($items, $cart);

        $cart->total_cart_price = CartService::calculatePrice($items);

        return $this->showOne(new CartResource($cart->load('cart_items')));
    }

    public function add_to_cart(CartItemRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $cart = Cart::where('user_id', auth()->id())->first();

        if (! $cart) {
            $cart = $this->authUser()->cart()->create();
        }

        $variant = Variant::where('uuid', $data['variant_id'])->first();

        $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

        if ($variant->status == 'unavailable') {
            return $this->errorResponse('Item is not available', 404);
        }

        if ((optional($cart_item)->count + $data['count']) > $variant->stock) {
            return $this->errorResponse('There are not enough items in stock', 404);
        }

        if ($cart_item) {
            $cart_item->count += $data['count'];
            $cart_item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'variant_id' => $variant->id,
                'count' => $data['count'],
            ]);
        }

        $cart->total_cart_price = CartService::calculatePrice($cart->cart_items);

        return $this->showOne(new CartResource($cart->load('cart_items')));
    }

    public function remove_from_cart(CartItemRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $variant = Variant::where('uuid', $data['variant_id'])->first();
        $cart = Cart::where('user_id', auth()->id())->first();

        if (! $cart) {
            return $this->errorResponse('Shopping cart missing', 404);
        }
        $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

        if ($cart_item->count < $data['count']) {
            return $this->errorResponse('You have less than '.$data['count'].' items', 422);
        }

        $cart_item->count -= $data['count'];

        if ($cart_item->count == 0) {
            $cart_item->delete();
        } else {
            $cart_item->save();
        }

        $cart->total_cart_price = CartService::calculatePrice($cart->cart_items);

        return $this->showOne(new CartResource($cart->load('cart_items')));
    }
}
