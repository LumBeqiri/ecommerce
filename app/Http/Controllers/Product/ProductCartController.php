<?php

namespace App\Http\Controllers\Product;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Session;

class ProductCartController extends ApiController
{
    public function addToCart(Request $request, $id){
        $product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        if($request['qty']){
            for($i=0;$i<$request['qty'];$i++){
                 $cart->add($product, $product->id);    
            }
        }else{
            $cart->add($product, $product->id);
        }

        $request->session()->put('cart', $cart);

        return $this->showOneObject($cart,200);
    }

    public function removeFromCart(Request $request, $id){
        $product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->remove($product, $product->id);

        $request->session()->put('cart', $cart);

        return $this->showOneObject($cart,200);
    }
}
