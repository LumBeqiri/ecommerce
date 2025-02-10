<?php

namespace App\Data;

use App\Models\Cart;
use Spatie\LaravelData\Data;

/**
 * @property Cart $cart The updated cart after applying the discount.
 * @property VariantDiscountData[] $variant_discounts An array of variant-level discount info.
 */
class DiscountResultData extends Data
{
    /**
     * @param  VariantDiscountData[]  $variant_discounts
     */
    public function __construct(
        public Cart $cart,
        public array $variant_discounts = []
    ) {}
}
