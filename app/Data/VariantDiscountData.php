<?php

namespace App\Data;

use App\Models\Variant;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class VariantDiscountData extends Data
{
    /**
     * @param  Variant    $variant
     * @param  float|int  $value
     * @param  string     $type
     */
    public function __construct(
        public Variant $variant,
        public float|int $value,
        public string $type
    ) {}
}