<?php

namespace App\Http\Controllers\Cart;

use App\Models\Cart;
// use App\Http\Requests\CartRequest;
use App\Models\User;
use App\values\Roles;
use App\Data\CartItemData;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CartResource;
use App\Exceptions\DiscountException;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Cart\UpdateCartRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class CartController extends ApiController
{
    public function index(): JsonResource
    {
        $user = $this->authUser();
        $carts = $this->userCartQueryForRole($user)->paginate(10);

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

    public function apply_discount(Request $request): JsonResponse|JsonResource
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

    protected function userCartQueryForRole(User $user): Builder
    {
        return match (true) {
            $user->hasRole(Roles::BUYER) => Cart::where('buyer_id', $user->buyer->id),
            $user->hasRole(Roles::VENDOR) => Cart::where('vendor_id', $user->vendor->id),
            $user->hasRole(Roles::STAFF) => Cart::where('vendor_id', $user->staff?->vendor_id),
            default => Cart::query(),
        };
    }

    public function syncCart(Request $request): JsonResource
    {

        $data = $request->validate([
            'cart_items' => 'required|array',
            'cart_items.*.variant_id' => 'required|string',  
            'cart_items.*.quantity' => 'required|integer|min:1',  
        ]);

        $cart = $this->authUser()->cart()->with('cart_items')->first();

        if (isset($data['cart_items']) && is_array($data['cart_items'])) {
            $cartItemsDTO = collect($data['cart_items'])
                ->map(fn ($item) => new CartItemData($item['variant_id'], $item['quantity']))
                ->all();

            $cart = CartService::syncItemsToCart($this->authUser(), $cartItemsDTO);
        }

        return new CartResource($cart->load('cart_items'));

    }
}
