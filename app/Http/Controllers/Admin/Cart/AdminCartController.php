<?php

namespace App\Http\Controllers\Admin\Cart;

use App\Models\Cart;
use App\Models\Variant;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\CartItemRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class AdminCartController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = QueryBuilder::for(Cart::class)
            ->allowedIncludes('user', 'cart_items')
            ->get();

        return $this->showAll(CartResource::collection($carts));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        $cartResult = QueryBuilder::for($cart)
            ->allowedIncludes('user', 'cart_items')
            ->first();
        return $this->showOne(new CartResource($cartResult));
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CartRequest $request, Cart $cart)
    {
        $data = $request->validated();

        $cart->is_closed = $data['is_closed'];
        $cart->save();

        return $this->showOne(new CartResource($cart));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();

        return $this->showMessage('Cart deleted Successfully', 200);
    }

    /**
     * @param CartItemRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function remove_from_cart(Request $request, Cart $cart, Variant $variant){

        $data = $request->validate([
            'count' => 'integer|min:1|max:500'
        ]);

        if(!$cart){
            return $this->errorResponse('Shopping cart missing', 404);
        }

        $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();
  
        if(!$request->has('count')){
            $cart_item->delete(); 
            return $this->showOne(new CartResource($cart->load('cart_items')));
        }
        if($cart_item->count < $data['count']){
            return $this->errorResponse('You have less than ' . $data['count'] . ' items', 422);
        }

        $cart_item->count -= $data['count'];

        if($cart_item->count == 0){
            $cart_item->delete(); 
        }
        else{
            $cart_item->save();
        }

        return $this->showOne(new CartResource($cart->load('cart_items')));
    }
}
