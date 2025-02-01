<?php

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class ProductData extends Data
{
    /**
     * @param int|null $id Bigint, unsigned, auto-increment, primary key.
     * @param string $ulid Char(26).
     * @param string $product_name Varchar(255).
     * @param int $vendor_id Bigint, unsigned.
     * @param string $status Varchar(255).
     * @param string $publish_status Varchar(255).
     * @param bool $discountable Tinyint(1).
     * @param int $origin_country Bigint, unsigned.
     * @param int $discount_id Bigint, unsigned.
     * @param CarbonImmutable|null $created_at Timestamp.
     * @param CarbonImmutable|null $updated_at Timestamp.
     * @param CarbonImmutable|null $deleted_at Timestamp.
     */
    public function __construct(
        public ?int $id,
        public ?string $ulid,
        public string $product_name,
        public int $vendor_id,
        public string $status,
        public string $publish_status,
        public ?bool $discountable,
        public int $origin_country_id,
        public ?int $discount_id,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public ?CarbonImmutable $created_at,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public ?CarbonImmutable $updated_at,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public ?CarbonImmutable $deleted_at,
        public ?array $categories
    ) {}
}
