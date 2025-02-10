<?php

namespace App\Http\Controllers\Cart;

use App\Exceptions\DiscountException;
// use App\Http\Requests\CartRequest;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CartController extends ApiController
{
    public function index() : JsonResource
    {
        $user = $this->authUser();
        $carts = null;
        if ($user->isAdmin()) {
            $carts = Cart::with(['cart_items', 'buyer'])->paginate(10);
        }
        if ($user->isBuyer()) {
            $carts = Cart::where('buyer_id', $user->buyer->id)->paginate(10);
        }
        if ($user->isStaff()) {
            $carts = Cart::where('vendor_id', $user->staff->vendor_id)->paginate(10);
        }
        if ($user->isVendor()) {
            $carts = Cart::where('vendor_id', $user->vendor->id)->paginate(10);
        }

        return CartResource::collection($carts);

    }

    public function show(Cart $cart): JsonResponse
    {
        $this->authorize('manageCart', $cart);

        return $this->showOne(new CartResource($cart->load('cart_items')));
    }

    public function update(UpdateCartRequest $request, Cart $cart): JsonResponse
    {

        $user = $this->authUser();

        if ($user->isBuyer()) {
            return $this->errorResponse('Action not allowed', 422);
        }
        $this->authorize('manageCart', $cart);

        $data = $request->validated();

        $cart->is_closed = $data['is_closed'];
        $cart->save();

        return $this->showOne(new CartResource($cart));
    }

    public function destroy(Cart $cart): JsonResponse
    {

        $user = $this->authUser();

        if ($user->isBuyer()) {
            return $this->errorResponse('Action not allowed', 422);
        }
        $this->authorize('manageCart', $cart);

        $cart->delete();

        return $this->showMessage('Cart deleted Successfully', 200);
    }

    public function apply_discount(Request $request) : JsonResponse| JsonResource
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
