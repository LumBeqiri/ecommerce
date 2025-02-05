<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class VariantData extends Data
{
    public function __construct(
        public string $variant_name,
        public string $sku,
        public string|Optional $barcode,
        public string|Optional $ean,
        public string|Optional $upc,
        public string $product_id,
        public ?string $variant_short_description,
        public ?string $variant_long_description,
        public int $stock,
        public bool $manage_inventory,
        public string $status,
        public string $publish_status,
        public bool|Optional $allow_backorder,
        public string|Optional $material,
        public int|Optional $weight,
        public int|Optional $length,
        public int|Optional $height,
        public int|Optional $width
    ) {}
}
