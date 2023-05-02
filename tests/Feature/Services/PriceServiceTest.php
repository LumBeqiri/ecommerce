<?php

use App\Models\Country;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Services\VariantPriceService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
});

it('can display currency with cent denomination', function () {
    Currency::factory()->create(['has_cents' => true]);
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    Product::factory()->create();
    Variant::factory()->create();

    $variantPriceWithCents = VariantPrice::factory()->create(['price' => 100, 'region_id' => $region->id]);

    $priceWithCents = VariantPriceService::variantPriceToDisplay($variantPriceWithCents);

    $this->assertEquals(1.00, $priceWithCents);
});

it('can display currency without cent denomination', function () {
    Currency::factory()->create(['has_cents' => false]);
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    Product::factory()->create();
    Variant::factory()->create();

    $variantPriceWithoutCents = VariantPrice::factory()->create(['price' => 10, 'region_id' => $region->id]);

    $priceWithoutCents = VariantPriceService::variantPriceToDisplay($variantPriceWithoutCents);

    $this->assertEquals(10.00, $priceWithoutCents);
});
