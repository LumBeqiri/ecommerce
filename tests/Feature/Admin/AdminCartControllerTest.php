<?php

use App\Models\Country;
use App\Models\Region;
use App\Models\TaxProvider;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);

    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
});
