<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class VariantPriceData extends Data
{
    public function __construct(
        public int $region_id,
        public int $price,
        public int|Optional $currency_id,
        public int $min_quantity,
        public int $max_quantity
    ) {}
}
