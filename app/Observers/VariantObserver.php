<?php

namespace App\Observers;

use App\Models\Media;
use App\Models\Variant;

class VariantObserver
{
    /**
     * Handle the Variant "deleted" event.
     *
     * @return void
     */
    public function deleted(Variant $variant)
    {
        $variant->attributes()->detach();

        Media::where('mediable_id', $variant->id)
            ->where('mediable_type', Variant::class)
            ->delete();
    }
}
