<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\PaperTypes\PaperTypeResource;
use App\Models\PaperType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PaperTypeResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves_to_array(): void
    {
        $model = PaperType::factory()->create(['label' => '?????']);
        $data = (new PaperTypeResource($model))->toArray(new Request);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }
}
