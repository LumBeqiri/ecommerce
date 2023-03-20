<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @return void
     */
    public function created(Product $product)
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     *
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

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

    /**
     * Handle the Product "restored" event.
     *
     * @return void
     */
    public function restored(Product $product)
    {
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
