<?php

use App\Models\Country;
use App\Models\Currency;
use App\Models\Region;
use App\Models\TaxProvider;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    Notification::fake();
    Bus::fake();
    Currency::factory()->count(5)->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
});

it('admin can update buyer first name and last name', function () {})->todo();

it('admin can update buyer city', function () {})->todo();

it('admin can update buyer country', function () {})->todo();

it('admin can update buyer phone', function () {})->todo();
