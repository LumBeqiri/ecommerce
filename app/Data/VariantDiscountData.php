<?php

namespace App\Data;

use App\Models\Variant;
use Spatie\LaravelData\Data;

class VariantDiscountData extends Data
{
    public function __construct(
        public Variant $variant,
        public float|int $value,
        public string $type
    ) {}
}
