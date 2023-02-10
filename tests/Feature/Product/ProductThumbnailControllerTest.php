<?php

use App\Http\Controllers\Product\ProductThumbnailController;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\Seller;
use App\Models\TaxProvider;
use Database\Seeders\CurrencySeeder;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    Storage::fake();
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
});

it('can upload product thumbnail', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $seller = Seller::factory()
            ->has(Product::factory(['thumbnail' => ''])->available()->count(1))
            ->create();
    $product = $seller->products()->first();

    $file = UploadedFile::fake()->image('avatar.jpg');

    login($seller);

    $response = $this->postJson(action([ProductThumbnailController::class, 'store'], ['product' => $product->uuid]),
        [
            'thumbnail' => $file,
        ]
    );

    $response->assertStatus(200);

    expect($response->json())
        ->thumbnail->toBe($file->hashname());

    $this->assertTrue(file_exists(public_path().'/img/'.$file->hashName()));
    $this->assertDatabaseHas(Product::class, ['thumbnail' => $file->hashName()]);
});
