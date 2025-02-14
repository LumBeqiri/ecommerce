<?php

namespace App\Http\Controllers\Buyer;

use App\Data\CartItemData;
use App\Exceptions\CartException;
use App\Exceptions\DiscountException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Cart\CartItemRequest;
use App\Http\Requests\Cart\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Models\Variant;
use App\Services\CartService;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class BuyerCartController extends ApiController
{
    public function index(User $user): JsonResponse
    {
        $cart = $user->buyer->cart()->with('cart_items')->first();

        return $this->showOne(new CartResource($cart));
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
}
