<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\ProductStocks\ProductStockResource;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProductStockResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $stock = ProductStock::factory()->create([
            ProductStock::PARAM_PRODUCT_ID => $product->id,
            ProductStock::PARAM_USER_ID => $user->id,
        ]);

        $data = (new ProductStockResource($stock))->toArray(new Request);
        $this->assertIsArray($data);
    }
}
