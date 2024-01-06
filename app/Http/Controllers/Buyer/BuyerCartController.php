<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Cart\CartItemRequest;
use App\Http\Requests\Cart\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\User;
use App\Models\Variant;
use App\Services\CartService;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuyerCartController extends ApiController
{
    public function index(User $buyer): JsonResponse
    {
        $cart = $buyer->cart()->with('cart_items')->first();

        return $this->showOne(new CartResource($cart));
    }

    // @phpstan-ignore-next-line
    public function store(CartRequest $request)
    {
        $data = $request->validated();
        $items = $data['items'];

        $cart = CartService::saveItemsToCart($items, auth()->user());

        if ($cart instanceof JsonResponse) {
            return $cart;
        }

        CartService::calculateCartPrice($cart);

        return $this->showOne(new CartResource($cart));
    }

    public function remove_from_cart(CartItemRequest $request): JsonResponse
    {
        $data = $request->validated();

        $variant = Variant::where('uuid', $data['variant_id'])->first();
        $cart = Cart::where('buyer_id', auth()->id())->first();

        if ($cart === null) {
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

    public function apply_discount(Request $request): array|JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ], [$request->code]);

        $cart = $this->authUser()->cart;

        return DiscountService::applyDiscount($cart, $request->code);
    }
}
