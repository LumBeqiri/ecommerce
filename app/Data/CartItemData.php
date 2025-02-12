<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CartItemData extends Data
{
    public function __construct(
        public string $variant_id,
        public int $quantity,
    ) {}
}
