<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CountryData extends Data
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $code,
        public ?string $region_id
    ) {}
}
