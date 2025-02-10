<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ProductData extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $ulid,
        public string $product_name,
        public int|Optional $vendor_id,
        public string $status,
        public string $publish_status,
        public bool|Optional $discountable,
        public int $origin_country_id,
        public ?int $discount_id,
        /** @var array<int> */
        public ?array $categories
    ) {}
}
