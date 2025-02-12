<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Cart;
use App\Models\User;
use App\Models\Variant;
use App\Models\CartItem;
use App\Data\CartItemData;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Exceptions\CartException;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CartResource;
use App\Exceptions\DiscountException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Cart\CartRequest;
use App\Http\Requests\Cart\CartItemRequest;
use Illuminate\Http\Resources\Json\JsonResource;

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
    
        // Convert each incoming item into a CartItemData instance.
        $cartItemsDTO = collect($data['items'])
            ->map(fn($item) => new CartItemData(
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

        $variant = Variant::where('ulid', $data['variant_id'])->first();

        /**
         * @var Cart $cart
         * */
        $cart = Cart::where('buyer_id', auth()->user()->buyer->id)->firstOrFail();

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

        $cart_item->quantity -= $data['quantity'];

        if ($cart_item->isEmpty()) {
            $cart_item->delete();
        } else {
            $cart_item->save();
        }

        CartService::calculateCartPrice($cart->refresh());

        return $this->showOne(new CartResource($cart->load('cart_items')));
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
