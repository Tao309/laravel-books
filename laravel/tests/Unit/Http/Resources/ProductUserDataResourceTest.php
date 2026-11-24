<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\ProductUserDatas\ProductUserDataResource;
use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProductUserDataResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $pud = ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $user->id,
        ]);

        $data = (new ProductUserDataResource($pud))->toArray(new Request);
        $this->assertIsArray($data);
    }
}
