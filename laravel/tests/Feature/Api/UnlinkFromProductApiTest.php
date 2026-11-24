<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

class UnlinkFromProductApiTest extends TestCase
{
    use ActsAsApiClient;

    public function test_unlink_clears_product_source(): void
    {
        $product = Product::factory()->forBook()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_USER_CREATED_ID => $this->user->id,
        ]);

        $response = $this->apiPost("/api/products/unlink/{$product->id}");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.book', null)
            ->assertJsonPath('data.source_product', null);

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ]);
    }

    public function test_unlink_rejects_product_without_source(): void
    {
        $product = Product::factory()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_USER_CREATED_ID => $this->user->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ]);

        $this->apiPost("/api/products/unlink/{$product->id}")
            ->assertBadRequest()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Product already no has Book or SourceProduct');
    }
}
