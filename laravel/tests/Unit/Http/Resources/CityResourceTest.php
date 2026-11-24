<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\Cities\CityResource;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class CityResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves_to_array(): void
    {
        $model = City::factory()->create(['code' => 'msk', 'title' => '??????']);
        $data = (new CityResource($model))->toArray(new Request);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }
}
