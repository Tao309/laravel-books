<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\BookSeries\BookSeriesResource;
use App\Models\BookSeries;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class BookSeriesResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves_to_array(): void
    {
        $model = BookSeries::factory()->create(['name' => '?????']);
        $data = (new BookSeriesResource($model))->toArray(new Request);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }
}
