<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\PublishingBrand\PublishingBrandResource;
use App\Models\PublishingBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PublishingBrandResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves_to_array(): void
    {
        $model = PublishingBrand::factory()->create(['name' => '?????']);
        $data = (new PublishingBrandResource($model))->toArray(new Request);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }
}
