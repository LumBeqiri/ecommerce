<?php

use App\Http\Controllers\Admin\TaxProvider\AdminTaxProviderController;
use App\Models\TaxProvider;
use App\Models\User;
use function Pest\Faker\faker;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
});

it('can return a list of tax providers', function () {
    TaxProvider::factory()->create();
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $response = $this->getJson(action([AdminTaxProviderController::class, 'index']));

    $response->assertOk();
});

it('can show a tax providers', function () {
    $TaxProvider = TaxProvider::factory()->create();
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $response = $this->getJson(action([AdminTaxProviderController::class, 'show'], $TaxProvider->uuid));

    $response->assertOk();
});

it('can store a tax provider', function () {
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $tax_provider = faker()->word();

    $response = $this->postJson(action([AdminTaxProviderController::class, 'store']), [
        'tax_provider' => $tax_provider,
        'is_installed' => 1,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(TaxProvider::class, ['tax_provider' => $tax_provider]);
});

it('can update a tax provider', function () {
    $taxProvider = TaxProvider::factory()->create();
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $tax_provider_title = faker()->word();

    $response = $this->putJson(action([AdminTaxProviderController::class, 'update'], $taxProvider->uuid), [
        'tax_provider' => $tax_provider_title,
        'is_installed' => 1,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(TaxProvider::class, ['tax_provider' => $tax_provider_title]);
});

it('can delete a tax provider', function () {
    $taxProvider = TaxProvider::factory()->create();
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([AdminTaxProviderController::class, 'destroy'], $taxProvider->uuid));

    $response->assertOk();

    $this->assertDatabaseMissing(TaxProvider::class, ['id' => $taxProvider->id]);
});
