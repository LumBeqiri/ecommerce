<?php
namespace App\Services;

use App\Models\Cart;
use App\Models\Variant;
use App\Models\CartItem;

class CartService{

    /**
     * @param mixed $items
     * 
     * @return int
     */
    public static function calculatePrice($items) 
    {
        
        $variant_ids = [];
        for($i = 0;$i < count($items);$i++){
            $variant_ids[$i] = $items[$i]['variant_id'];
        }

        $variant_prices = Variant::whereIn('id', $variant_ids)->pluck('price');

        $total = 0;

        foreach($variant_prices as $price) {
            $total += $price;
        }

        return $total;
    }


    public static function moveCartFromCookieToDB($items, $user){
        
        $cart = Cart::updateOrCreate(['user_id' => $user->id]);

        foreach($items as $item ){
           
            $variant = Variant::where('uuid', $item['variant_id'])->first();

            $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

            if(! $cart_item){
                CartItem::create([
                    'cart_id' => $cart->id,
                    'variant_id' => $variant->id,
                    'count' => $item['count']
                ]);
            }
        }

        return $cart;
        
    }

}