<?php

namespace App\Http\Controllers\Cart;

use App\Models\Cart;
use App\Models\User;
use App\Models\Buyer;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Controllers\ApiController;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Builder;

class VariantCartController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('id', 151)->first();

        return $this->showAll(CartResource::collection($user->carts()->with('cart_items')->get()));
    }



    /**
     * Store in storage
     * 
     * @param CartRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(CartRequest $request)
    {
        $data = $request->validated();
        $items = $data['items'];

   
        $cart = Cart::updateOrCreate(['user_id' => auth()->id()]);

        $price = 0;
        foreach($items as $item){
            CartItem::UpdateOrCreate(
                [
                    'cart_id' => $cart->id,
                    'variant_id' => $item['variant_id'],
                    'count' => $item['count']
                ]);
        }

        return $this->showOne(new CartResource($cart->load('cart_items')));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
