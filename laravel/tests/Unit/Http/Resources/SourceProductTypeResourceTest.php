<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\SourceProductTypes\SourceProductTypeResource;
use App\Models\SourceProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SourceProductTypeResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves_to_array(): void
    {
        $model = SourceProductType::factory()->create(['name' => '???', 'code' => 'type-' . uniqid()]);
        $data = (new SourceProductTypeResource($model))->toArray(new Request);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }
}
