<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Variant;

class ProductObserver
{
    /**
     * Handle the Product "deleting" event.
     *
     * @return void
     */
    public function deleting(Product $product)
    {
        $product->variants->each(function (Variant $variant) {
            $variant->media()->delete();
            $variant->variant_prices()->delete();
            $variant->delete();
        });
    }
}
