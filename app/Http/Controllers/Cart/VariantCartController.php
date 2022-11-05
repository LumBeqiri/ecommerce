<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\ApiController;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\CartResource;
use App\Models\User;

class VariantCartController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return $this->showAll(CartItemResource::collection($user->cart()->with('cart_items'))->get());
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
        $data  = $request->validated();
        $cart = Cart::create();

        $cart->cart_items()->create($data);

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
