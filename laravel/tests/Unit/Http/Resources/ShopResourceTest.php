<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\Shops\ShopResource;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ShopResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves_to_array(): void
    {
        $model = Shop::factory()->create([
            'type' => 't-' . substr(uniqid(), -8),
            'domain' => 'example.com',
            'url' => 'https://example.com',
        ]);
        $data = (new ShopResource($model))->toArray(new Request);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }
}
