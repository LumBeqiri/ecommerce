<?php

namespace App\Data;

use App\Models\Region;
use Brick\Money\Money;
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

    /**
     * Build the VariantPriceData from request data.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromRequest(array $data): self
    {
        /** @var \App\Models\Region $region */
        $region = Region::findOrFail($data['region_id']);

        return new self(
            region_id: $region->id,
            price: $data['price'],
            currency_id: $region->currency_id,
            min_quantity: $data['min_quantity'] ?? 1,
            max_quantity: $data['max_quantity'] ?? PHP_INT_MAX
        );
    }

    public function toMoneyAmount(Region $region): int
    {
        return Money::of($this->price, $region->currency->code)
            ->getMinorAmount()
            ->toInt();
    }
}
