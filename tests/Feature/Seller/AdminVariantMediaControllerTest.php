<?php

use App\Models\User;
use App\Models\Media;
use App\Models\Region;
use App\Models\Country;
use App\Models\Product;
use App\Models\Variant;
use App\Models\TaxProvider;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Database\Seeders\CurrencySeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\Product\AdminVariantMediaController;


beforeEach(function () {
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
    Storage::fake();
});

it('can upload one variant image ', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Product::factory()->available()->create();
    $variant = Variant::factory()->create();
    $user = User::factory()->create();

    $file = UploadedFile::fake()->image('avatar.jpg');

    login($user);

    $response = $this->postJson(action([AdminVariantMediaController::class, 'store'], [$variant->uuid]),
        [
            'medias' => [$file],
        ]
    );

    $response->assertStatus(200);

    $this->assertDatabaseHas(Media::class, [
        'mediable_type' => Variant::class,
        'mediable_id' => $variant->id,
        'name' => $file->hashname(),
    ]);
});

it('can upload more than one variant image ', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Product::factory()->available()->create();
    $variant = Variant::factory()->create();
    $user = User::factory()->create();

    $file1 = UploadedFile::fake()->image('avatar.jpg');
    $file2 = UploadedFile::fake()->image('avatar.jpg');
    $file3 = UploadedFile::fake()->image('avatar.jpg');

    $files[] = $file1;
    $files[] = $file2;
    $files[] = $file3;

    login($user);

    $response = $this->postJson(action([SellerVariantMediaController::class, 'store'], [$variant->uuid]),
        [
            'medias' => [$file1, $file2, $file3],
        ]
    );

    $response->assertStatus(200);

    foreach ($files as $file) {
        $this->assertDatabaseHas(Media::class, [
            'mediable_type' => Variant::class,
            'mediable_id' => $variant->id,
            'name' => $file->hashname(),
        ]);
    }
});

it('can delete one variant image ', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Product::factory()->available()->create();
    $variant = Variant::factory()->create();
    $user = User::factory()->create();

    $media = Media::factory()->create(['mediable_id' => $variant->id, 'mediable_type' => Variant::class]);

    login($user);

    $response = $this->deleteJson(action([SellerVariantMediaController::class, 'destroy'], [$variant->uuid, $media->uuid]));

    $response->assertStatus(200);

    $this->assertDatabaseMissing(Media::class, [
        'uuid' => $media->uuid,
    ]);
});
