<?php

namespace App\Http\Controllers\Admin\Cart;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AdminCartController extends ApiController
{
    public function index(): JsonResponse
    {
        $carts = QueryBuilder::for(Cart::class)
            ->allowedIncludes('user', 'cart_items')
            ->get();

        return $this->showAll(CartResource::collection($carts));
    }

    public function show(Cart $cart): JsonResponse
    {
        $cartResult = QueryBuilder::for($cart)
            ->allowedIncludes('user', 'cart_items')
            ->first();

        return $this->showOne(new CartResource($cartResult));
    }

    public function update(CartRequest $request, Cart $cart): JsonResponse
    {
        $data = $request->validated();

        $cart->is_closed = $data['is_closed'];
        $cart->save();

        return $this->showOne(new CartResource($cart));
    }

    public function destroy(Cart $cart): JsonResponse
    {
        $cart->delete();

        return $this->showMessage('Cart deleted Successfully', 200);
    }

    public function remove_from_cart(Request $request, Cart $cart, Variant $variant): JsonResponse
    {
        $data = $request->validate([
            'count' => 'integer|min:1|max:500',
        ]);

        $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

        if (! $request->has('count')) {
            $cart_item->delete();

            return $this->showOne(new CartResource($cart->load('cart_items')));
        }
        if ($cart_item->count < $data['count']) {
            return $this->errorResponse('You have less than '.$data['count'].' items', 422);
        }

        $cart_item->count -= $data['count'];

        if ($cart_item->count == 0) {
            $cart_item->delete();
        } else {
            $cart_item->save();
        }

        return $this->showOne(new CartResource($cart->load('cart_items')));
    }
}
