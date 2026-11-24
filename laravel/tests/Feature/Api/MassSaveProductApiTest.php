<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

class MassSaveProductApiTest extends TestCase
{
    use ActsAsApiClient;

    public function test_mass_save_creates_and_updates_products(): void
    {
        $existing = Product::factory()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_USER_CREATED_ID => $this->user->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
            Product::PARAM_SHOP_PRODUCT_ID => 'mass-save-existing-1',
        ]);

        $response = $this->apiPost('/api/products/mass-save', ['items' => [
            [
                Product::PARAM_SHOP_PRODUCT_ID => 'mass-save-new-000001',
                Product::PARAM_SHOP => ['id' => 1],
                Product::PARAM_TITLE => 'New product title',
                Product::PARAM_PRICES => [['price' => 100, 'date' => '2026.11.01']],
                Product::PARAM_STOCKS => [['qty' => 2, 'date' => '2026.11.01']],
            ],
            [
                Product::PARAM_ID => $existing->id,
                Product::PARAM_SHOP_PRODUCT_ID => $existing->shop_product_id,
                Product::PARAM_SHOP => ['id' => 1],
                Product::PARAM_TITLE => 'Updated product title',
                Product::PARAM_PRICES => [['price' => 150, 'date' => '2026.11.01']],
                Product::PARAM_STOCKS => [['qty' => 3, 'date' => '2026.11.01']],
            ],
        ]]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Products saved')
            ->assertJsonPath('data.products_count', 2)
            ->assertJsonPath('data.saved_count', 2)
            ->assertJsonPath('data.errors_count', 0)
            ->assertJsonPath('data.meta.count', 2)
            ->assertJsonCount(2, 'data.items');

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_SHOP_PRODUCT_ID => 'mass-save-new-000001',
            Product::PARAM_TITLE => 'New product title',
        ]);
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $existing->id,
            Product::PARAM_TITLE => 'Updated product title',
        ]);
    }

    public function test_mass_save_validates_items(): void
    {
        $this->apiPost('/api/products/mass-save', ['items' => []])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Validation error')
            ->assertJsonStructure(['errors' => ['items']]);
    }
}
