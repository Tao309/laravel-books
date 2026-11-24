<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\ProductPrices\ProductPriceResource;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProductPriceResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $price = ProductPrice::factory()->create([
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
            ProductPrice::PARAM_USER_ID => $user->id,
        ]);

        $data = (new ProductPriceResource($price))->toArray(new Request);
        $this->assertIsArray($data);
    }
}
