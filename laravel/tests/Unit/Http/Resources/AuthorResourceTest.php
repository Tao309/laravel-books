<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\Authors\AuthorResource;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthorResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves_to_array(): void
    {
        $model = Author::factory()->create(['first_name' => '???', 'last_name' => '???????']);
        $data = (new AuthorResource($model))->toArray(new Request);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }
}
