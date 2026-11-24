<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\PublishingHouses\PublishingHouseResource;
use App\Models\PublishingHouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PublishingHouseResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves_to_array(): void
    {
        $model = PublishingHouse::factory()->create(['name' => '???']);
        $data = (new PublishingHouseResource($model))->toArray(new Request);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }
}
