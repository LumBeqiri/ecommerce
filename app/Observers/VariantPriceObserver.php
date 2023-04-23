<?php

namespace App\Observers;

use App\Models\VariantPrice;

class VariantPriceObserver
{
    /**
     * Handle the VariantPrice "created" event.
     */
    public function created(VariantPrice $variantPrice): void
    {
    }

    /**
     * Handle the VariantPrice "updated" event.
     */
    public function updated(VariantPrice $variantPrice): void
    {
    }

    /**
     * Handle the VariantPrice "deleted" event.
     */
    public function deleted(VariantPrice $variantPrice): void
    {
        //
    }

    /**
     * Handle the VariantPrice "restored" event.
     */
    public function restored(VariantPrice $variantPrice): void
    {
        //
    }

    /**
     * Handle the VariantPrice "force deleted" event.
     */
    public function forceDeleted(VariantPrice $variantPrice): void
    {
        //
    }
}
