<?php

namespace App\Http\Controllers\Admin\Cart;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Cart\UpdateCartRequest;
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
        // return paginated carts
        $carts = QueryBuilder::for(Cart::class)
            ->allowedIncludes('buyer', 'cart_items')
            ->paginate(10);

        return $this->showAll(CartResource::collection($carts));
    }

    public function show(Cart $cart): JsonResponse
    {
        $cartResult = QueryBuilder::for($cart)
            ->allowedIncludes('buyer', 'cart_items')
            ->first();

        return $this->showOne(new CartResource($cartResult));
    }

    public function update(UpdateCartRequest $request, Cart $cart): JsonResponse
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
            'quantity' => 'integer|min:1|max:500',
        ]);

        $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

        if (! $request->has('quantity')) {
            $cart_item->delete();

            return $this->showOne(new CartResource($cart->load('cart_items')));
        }
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
