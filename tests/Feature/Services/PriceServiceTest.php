<?php

use App\Models\Country;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Services\PriceService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
});

it('can display currency', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    Product::factory()->create();
    Variant::factory()->create();

    $currencyWithCents = Currency::factory()->create(['has_cents' => true]);
    $currencyWithoutCents = Currency::factory()->create(['has_cents' => false]);
    $variantPriceWithCents = VariantPrice::factory()->create(['currency_id' => $currencyWithCents->id, 'price' => 100]);
    $variantPriceWithoutCents = VariantPrice::factory()->create(['currency_id' => $currencyWithoutCents->id, 'price' => 10]);

    $priceWithCents = PriceService::priceToDisplay($variantPriceWithCents);
    $priceWithoutCents = PriceService::priceToDisplay($variantPriceWithoutCents);

    $this->assertEquals(1.00, $priceWithCents);
    $this->assertEquals(10.00, $priceWithoutCents);
});
