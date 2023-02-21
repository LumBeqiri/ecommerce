<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class CartController extends ApiController
{
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
}
