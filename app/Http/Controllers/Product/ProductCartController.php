<?php

namespace App\Http\Controllers\Product;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
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

    public function getCart(Request $request){
        $oldCart = $request->session()->get('cart');
        $cart = new Cart($oldCart);
        $cart_items = $cart->items;

  
      //  print_r($cart);
        // $array = array();

        // if($cart_items){
        //     foreach($cart_items as $key=>$value){
        //         $array[$key]['id'] = $value['item'];
        //         $array[$key]['qty'] = $value['qty'];
        //     }

        //     foreach($array as $a){
        //         print_r($a['id']);
        //     }
        // }


        


        foreach($cart->items as $item){
            echo json_encode($item);
            
        }

        // $array = array();

        // foreach ($cart_items as $key =>$value){
        //     $array[$key]['id'] = $value['item'];
        //     $array[$key]['qty'] = $value['qty'];
        // }

        // print_r($array);

      // return $this->showOneObject($cart);

    }


}
