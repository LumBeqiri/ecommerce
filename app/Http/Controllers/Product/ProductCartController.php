<?php

namespace App\Http\Controllers\Product;

use App\Models\SessionCart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Order;
use Illuminate\Support\Facades\Session;

class ProductCartController extends ApiController
{
    public function addToCart(Request $request, $id){
        $product = Product::findOrFail($id);
      
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new SessionCart($oldCart);
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
        $cart = new SessionCart($oldCart);
        $cart->remove($product, $product->id);

        $request->session()->put('cart', $cart);

        return $this->showOneObject($cart,200);
    }

    public function checkout(Request $request){
        $oldCart = $request->session()->get('cart');
        $cart = new SessionCart($oldCart);
    //   print_r($cart);
        $products= $cart->product_ids;

       
        print_r($products);

        $data = [
            'buyer_id' => 2,
            'ship_name' => 'Lejla',
            'ship_address' => 'dalip',
            'ship_city' => 'gjilan',
            'ship_state' => 'kosovo',
            'order_tax' => 18,
            'order_date' => now(),
            'total' => $cart->totalPrice,
            'order_shipped' => Order::SHIPPED_ORDER,
            'order_email' => 'lejla@test.com',
            'order_phone' => '+383 44 123 456',
            'payment_id' => 43,
        ];

         $order = Order::create($data);

         $order->products()->attach($products);

         return $order;
         
    }


    public function getCart(Request $request){
        $oldCart = $request->session()->get('cart');
        $cart = new SessionCart($oldCart);
        $cart_items = $cart->items;
        print_r($cart);

    }


}
