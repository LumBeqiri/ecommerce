<?php

namespace App\Http\Controllers\Buyer;

use App\Exceptions\CartException;
use App\Exceptions\DiscountException;
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
use Illuminate\Support\Facades\DB;

class BuyerCartController extends ApiController
{
    public function index(User $user): JsonResponse
    {
        $cart = $user->buyer->cart()->with('cart_items')->first();

        return $this->showOne(new CartResource($cart));
    }

    // @phpstan-ignore-next-line
    public function add_to_cart(CartRequest $request)
    {
        $data = $request->validated();
        $items = $data['items'];

        try {
            $cart = CartService::saveItemsToCart($items);
            CartService::calculateCartPrice($cart);
        } catch (CartException $ex) {
            return $this->showError($ex->getMessage(), $ex->getCode());
        }

        return $this->showOne(new CartResource($cart));
    }

    public function remove_from_cart(CartItemRequest $request): JsonResponse
    {
        $data = $request->validated();

        $variant = Variant::where('uuid', $data['variant_id'])->first();
        $cart = Cart::where('buyer_id', auth()->user()->buyer->id)->first();

        if ($cart === null) {
            return $this->errorResponse('Shopping cart missing', 404);
        }

        if (count($cart->cart_items) == 0) {
            $cart->total_cart_price = 0;
            $cart->save();

            return $this->showOne(new CartResource($cart));
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

        CartService::calculateCartPrice($cart->refresh());

        return $this->showOne(new CartResource($cart->load('cart_items')));
    }

    public function apply_discount(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ], [$request->code]);

        $cart = auth()->user()->buyer->cart;
        DB::beginTransaction();
        try {
            DiscountService::applyDiscount($cart, $request->code);
            DB::commit();
        } catch (DiscountException $ex) {
            DB::rollBack();

            return $this->showError($ex->getMessage(), $ex->getCode());
        }

        return new CartResource($cart);
    }
}
