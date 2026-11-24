<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\ProductUserData;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

class ArchiveProductApiTest extends TestCase
{
    use ActsAsApiClient;

    public function test_archive_returns_product_with_updated_user_data(): void
    {
        $product = Product::factory()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_USER_CREATED_ID => $this->user->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
            Product::PARAM_SHOP_PRODUCT_ID => 'archive-api-000001',
        ]);

        ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
            ProductUserData::PARAM_IS_ARCHIVE => false,
        ]);

        $response = $this->apiPost('/api/products/archive', [
            Product::PARAM_SHOP_PRODUCT_ID => $product->shop_product_id,
            ProductUserData::PARAM_IS_ARCHIVE => true,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.product_user_data.is_archive', true);

        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
            ProductUserData::PARAM_IS_ARCHIVE => true,
        ]);
    }

    public function test_archive_reports_missing_product_with_require_to_create_flag(): void
    {
        $this->apiPost('/api/products/archive', [
            Product::PARAM_SHOP_PRODUCT_ID => 'missing-api-000001',
            ProductUserData::PARAM_IS_ARCHIVE => true,
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Product is not exists')
            ->assertJsonPath('data.require_to_create', true);
    }

    public function test_archive_validates_payload(): void
    {
        $this->apiPost('/api/products/archive', [
            Product::PARAM_SHOP_PRODUCT_ID => 'x',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Validation error')
            ->assertJsonStructure(['errors' => ['shop_product_id', 'is_archive']]);
    }
}
