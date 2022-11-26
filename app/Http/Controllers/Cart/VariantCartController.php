<?php

namespace App\Http\Controllers\Cart;

use App\Models\Cart;
use App\Models\User;
use App\Models\Variant;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Http\Requests\CartItemRequest;
use App\Http\Controllers\ApiController;
use App\Http\Resources\CartItemResource;

class VariantCartController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showAll(CartResource::collection(Cart::all()->with('cart_items')->get()));
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

        if($request->hasCookie('cart')){
            $cookie_cart = $request->cookie('cart');
            $items = json_decode($cookie_cart, true);
            $items = $items['items'];
   
        }
        foreach($items as $item ){
           
            $variant = Variant::where('uuid', $item['variant_id'])->first();

            $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

            if($cart_item){
                $cart_item->count += $item['count'];
                $cart_item->save();
            }else{
                CartItem::create([
                    'cart_id' => $cart->id,
                    'variant_id' => $variant->id,
                    'count' => $item['count']
                ]);
            }

        }
             
        $cart->total_cart_price = CartService::calculatePrice($items);
   
        return $this->showOne(new CartResource($cart->load('cart_items')));

    }


    public function add_to_cart(CartItemRequest $request)
    {

        $data = $request->validated();
        $cart = Cart::where('user_id', auth()->id())->first();

        $variant = Variant::where('uuid', $data['variant_id'])->first();

        $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();


        if($variant->status == 'unavailable'){
            return $this->errorResponse('Item is not available', 404);
        }

        //check if there's enough items in stock
        if($cart_item->count > $variant->stock){
            return $this->errorResponse('Item is not available', 404);
        }


        if($cart_item){
            $cart_item->count += $data['count'];
            $cart_item->save();
           
        }else{
            CartItem::create([
                'cart_id' => $cart->id,
                'variant_id' => $variant->id,
                'count' => $data['count']
            ]);
        }

        return $this->showOne(new CartResource($cart->load('cart_items')));
    }


    public function remove_from_cart(CartItemRequest $request){

        $data = $request->validated();

        $variant = Variant::where('uuid', $data['variant_id'])->first();
        $cart = Cart::where('user_id', auth()->id())->first();
        $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();
  
        if($cart_item->count < $data['count']){
            return $this->errorResponse('You have less than ' . $data['count'] . ' items', 422);
        }

        $cart_item->count = $cart_item->count - $data['count'];

        if( $cart_item->count == 0 ){
            $cart_item->delete(); 
        }
        else{
            $cart_item->save();
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
