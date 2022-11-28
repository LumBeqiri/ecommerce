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
            $total += (int) $price;
        }

        return PriceService::priceToEuro($total);
    }


    public static function moveCartFromCookieToDB($items, $user){

        $cart = Cart::updateOrCreate(['user_id' => $user->id]);

        foreach($items as $item ){
           
            $variant = Variant::where('uuid', $item['variant_id'])->firstOrFail();
  
            $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

            // if cart-item doesnt exist and there's enough qty then create 
            if(! $cart_item){
                if($item['count'] < $variant->stock){
                    CartItem::create([
                        'cart_id' => $cart->id,
                        'variant_id' => $variant->id,
                        'count' => $item['count']
                    ]);
                }
            }
        }

        return $cart;
        
    }


    public static function validateCookieItems(array $items) : bool
    {
        foreach($items as $item){
            if(Variant::find($item['variant_id'])){
                return false;
            }
            if($item['count'] < 1){
                return false;
            }
        }

        return true;
    }

}