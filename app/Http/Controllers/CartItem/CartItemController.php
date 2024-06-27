<?php

namespace App\Http\Controllers\CartItem;

use App\Exceptions\CartException;
// use App\Http\Requests\CartRequest;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Cart\CartItemRequest;
use App\Http\Requests\Cart\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Variant;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CartItemController extends ApiController
{
    public function add_to_cart(CartRequest $request)
    {
        $data = $request->validated();
        $items = $data['items'];

        DB::beginTransaction();
        try {
            $cart = CartService::saveItemsToCart($items);
            CartService::calculateCartPrice($cart);
            DB::commit();
        } catch (CartException $ex) {
            DB::rollBack();

            return $this->showError($ex->getMessage(), $ex->getCode());
        }

        return $this->showOne(new CartResource($cart));
    }

    public function remove_from_cart(CartItemRequest $request): JsonResponse
    {
        $data = $request->validated();

        $variant = Variant::where('uuid', $data['variant_id'])->first();

        /**
         * @var Cart $cart
         * */
        $cart = Cart::where('buyer_id', auth()->user()->buyer->id)->first();

        if ($cart === null) {
            return $this->errorResponse('Shopping cart missing', 404);
        }

        if ($cart->isEmpty()) {
            $cart->total_cart_price = 0;
            $cart->save();

            return $this->showOne(new CartResource($cart));
        }

        /**
         * @var CartItem $cart_item
         */
        $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

        if ($cart_item->quantity < $data['quantity']) {
            return $this->errorResponse('You have less than '.$data['quantity'].' items', 422);
        }

        DB::beginTransaction();
        try {

            $cart_item->quantity -= $data['quantity'];

            if ($cart_item->isEmpty()) {
                $cart_item->delete();
            } else {
                $cart_item->save();
            }

            CartService::calculateCartPrice($cart->refresh());
            DB::commit();
        } catch (CartException $ex) {
            DB::rollBack();

            return $this->showError($ex->getMessage(), $ex->getCode());
        }

        return $this->showOne(new CartResource($cart->load('cart_items')));
    }
}
