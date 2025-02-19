<?php

namespace App\Http\Controllers\Cart;

use App\Data\CartItemData;
use App\Exceptions\CartException;
use App\Exceptions\DiscountException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Cart\CartItemRequest;
use App\Http\Requests\Cart\CartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\User;
use App\Services\CartService;
use App\Services\DiscountService;
use App\values\Roles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

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
        $cartQuery = Cart::with('cart_items');

        return match (true) {
            $user->hasRole(Roles::BUYER) => $cartQuery->where('buyer_id', $user->buyer->id),
            $user->hasRole(Roles::VENDOR) => $cartQuery->where('vendor_id', $user->vendor->id),
            $user->hasRole(Roles::STAFF) => $cartQuery->where('vendor_id', $user->staff?->vendor_id),
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

    public function add_to_cart(CartRequest $request): JsonResponse
    {
        $data = $request->validated();

        /**
         * @var array<int, array{variant_id: string, quantity: int}> $items
         */
        $items = $data['items'];

        $cartItemsDTO = collect($items)
            ->map(fn (array $item): CartItemData => new CartItemData(
                variant_id: $item['variant_id'],
                quantity: $item['quantity']
            ))
            ->all();

        try {
            $cart = CartService::saveItemsToCart($cartItemsDTO);
            CartService::calculateCartPrice($cart);
        } catch (CartException $ex) {
            return $this->showError($ex->getMessage(), $ex->getCode());
        }

        return $this->showOne(new CartResource($cart));
    }

    public function remove_from_cart(CartItemRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $cart = CartService::removeItemFromCart($data);
        } catch (CartException $ex) {
            return $this->showError($ex->getMessage(), $ex->getCode());
        } catch (\Exception $ex) {
            return $this->showError('An unexpected error occurred', 500);
        }

        return $this->showOne(new CartResource($cart));
    }
}
