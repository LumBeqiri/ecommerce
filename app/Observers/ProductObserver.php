<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "deleting" event.
     *
     * @return void
     */
    public function deleting(Product $product)
    {
        $product->variants->each(function ($variant) {
            $variant->medias()->delete();
        });
    }
}
