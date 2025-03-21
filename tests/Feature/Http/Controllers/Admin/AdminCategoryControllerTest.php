<?php

use App\Http\Controllers\Admin\Category\AdminCategoryController;
use App\Models\Category;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {

    Currency::factory()->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $this->seed(RoleAndPermissionSeeder::class);
    Notification::fake();
    Bus::fake();
});

test('admin can show categories', function () {

    $user = User::factory()->create();
    $user->assignRole('admin');

    Category::factory()->create();

    login($user);

    $response = $this->getJson(action([AdminCategoryController::class, 'index']));

    $response->assertOk();
});

test('admin can update category name', function () {
    $category = Category::factory()->create();

    $user = User::factory()->create(['email' => 'lumadin@example.com']);
    $user->assignRole('admin');
    $updatedName = 'new name';

    login($user);

    $response = $this->putJson(action([AdminCategoryController::class, 'update'], $category->ulid), [
        'name' => $updatedName,
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas(Category::class, ['name' => $updatedName]);
});

test('admin can update category slug', function () {
    $category = Category::factory()->create();

    $user = User::factory()->create(['email' => 'lumadin@example.com']);
    $user->assignRole('admin');
    $updatedSlug = 'new-slug';

    login($user);

    $response = $this->putJson(action([AdminCategoryController::class, 'update'], $category->ulid), [
        'slug' => $updatedSlug,
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas(Category::class, ['slug' => $updatedSlug]);
});

test('admin can update category description', function () {
    $category = Category::factory()->create();

    $user = User::factory()->create(['email' => 'lumadin@example.com']);
    $user->assignRole('admin');
    $updatedDescription = 'new-slug';

    login($user);

    $response = $this->putJson(action([AdminCategoryController::class, 'update'], $category->ulid), [
        'description' => $updatedDescription,
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas(Category::class, ['description' => $updatedDescription]);
});

test('admin can update category parent', function () {
    $childCategory = Category::factory()->create();
    $parentCategory = Category::factory()->create();

    $user = User::factory()->create(['email' => 'lumadin@example.com']);
    $user->assignRole('admin');

    login($user);

    $response = $this->putJson(action([AdminCategoryController::class, 'update'], $childCategory->ulid), [
        'parent' => $parentCategory->ulid,
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas(Category::class, ['parent_id' => $parentCategory->id]);
});

test('admin can delete category', function () {
    $parentCategory = Category::factory()->create();
    $childCategory = Category::factory()->create(['parent_id' => $parentCategory->id]);

    $user = User::factory()->create(['email' => 'lumadin@example.com']);
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([AdminCategoryController::class, 'destroy'], $parentCategory->ulid));

    $response->assertStatus(200);

    $this->assertDatabaseMissing(Category::class, ['parent_id' => $parentCategory->id]);
    $this->assertDatabaseMissing(Category::class, ['id' => $childCategory->id]);
});
