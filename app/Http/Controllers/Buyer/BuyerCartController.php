<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CartItemRequest;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\User;
use App\Models\Variant;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;

class BuyerCartController extends ApiController
{
    public function index(User $buyer): JsonResponse
    {
        $cart = $buyer->cart()->with('cart_items')->first();

        return $this->showOne(new CartResource($cart));
    }

    public function store(CartRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $items = $data['items'];

        $cart = CartService::saveItemsToCart($items, auth()->user());

        if ($cart instanceof JsonResponse) {
            return $cart;
        }

        // $cart->total_cart_price = CartService::calculatePrice($items);

        return $this->showOne(new CartResource($cart));
    }

    public function remove_from_cart(CartItemRequest $request): JsonResponse
    {
        $data = $request->validated();

        $variant = Variant::where('uuid', $data['variant_id'])->first();
        $cart = Cart::where('user_id', auth()->id())->first();

        if (! $cart) {
            return $this->errorResponse('Shopping cart missing', 404);
        }
        $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

        if ($cart_item->quantity < $data['quantity']) {
            return $this->errorResponse('You have less than '.$data['quantity'].' items', 422);
        }

        $cart_item->quantity -= $data['quantity'];

        if ($cart_item->quantity == 0) {
            $cart_item->delete();
        } else {
            $cart_item->save();
        }

        return $this->showOne(new CartResource($cart->load('cart_items')));
    }
}
