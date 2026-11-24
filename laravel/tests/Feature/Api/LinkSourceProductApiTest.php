<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\SourceProduct;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

class LinkSourceProductApiTest extends TestCase
{
    use ActsAsApiClient;

    public function test_link_source_product_sets_product_source(): void
    {
        $product = $this->makeProduct();
        $sourceProduct = SourceProduct::factory()->create([
            SourceProduct::PARAM_USER_CREATED_ID => $this->user->id,
        ]);

        $response = $this->apiPost("/api/products/link_source_product/{$product->id}/{$sourceProduct->id}");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.source_product.id', $sourceProduct->id)
            ->assertJsonPath('data.book', null);

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_SOURCE_TYPE => SourceProduct::class,
            Product::PARAM_SOURCE_ID => $sourceProduct->id,
        ]);
    }

    public function test_link_source_product_rejects_product_that_already_has_a_source(): void
    {
        $product = Product::factory()->forSourceProduct()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_USER_CREATED_ID => $this->user->id,
        ]);
        $sourceProduct = SourceProduct::factory()->create();

        $this->apiPost("/api/products/link_source_product/{$product->id}/{$sourceProduct->id}")
            ->assertBadRequest()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Product already has Book or SourceProduct');
    }

    public function test_link_source_product_reports_missing_source_product(): void
    {
        $product = $this->makeProduct();

        $this->apiPost("/api/products/link_source_product/{$product->id}/999999")
            ->assertBadRequest()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Not found SourceProduct by id = 999999');
    }

    private function makeProduct(): Product
    {
        return Product::factory()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_USER_CREATED_ID => $this->user->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ]);
    }
}
