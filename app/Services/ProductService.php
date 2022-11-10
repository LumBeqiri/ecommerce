<?php
namespace App\Services;

use App\Models\Variant;

class Productservice{

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

}