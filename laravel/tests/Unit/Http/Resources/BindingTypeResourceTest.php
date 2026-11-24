<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\BindingTypes\BindingTypeResource;
use App\Models\BindingType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class BindingTypeResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves_to_array(): void
    {
        $model = BindingType::factory()->create(['label' => '???????']);
        $data = (new BindingTypeResource($model))->toArray(new Request);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }
}
