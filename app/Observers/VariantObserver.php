<?php

namespace App\Observers;

use App\Models\CartItem;
use App\Models\Media;
use App\Models\Variant;

class VariantObserver
{
    /**
     * Handle the Variant "created" event.
     *
     * @param  \App\Models\Variant  $variant
     * @return void
     */
    public function created(Variant $variant)
    {
        //
    }

    /**
     * Handle the Variant "updated" event.
     *
     * @param  \App\Models\Variant  $variant
     * @return void
     */
    public function updated(Variant $variant)
    {
        //
    }

    /**
     * Handle the Variant "deleted" event.
     *
     * @param  \App\Models\Variant  $variant
     * @return void
     */
    public function deleted(Variant $variant)
    {
        $variant->attributes()->detach();

        Media::where('mediable_id', $variant->id)
            ->where('mediable_type', Variant::class)
            ->delete();
    }

    /**
     * Handle the Variant "restored" event.
     *
     * @param  \App\Models\Variant  $variant
     * @return void
     */
    public function restored(Variant $variant)
    {
        //
    }

    /**
     * Handle the Variant "force deleted" event.
     *
     * @param  \App\Models\Variant  $variant
     * @return void
     */
    public function forceDeleted(Variant $variant)
    {
        //
    }
}
