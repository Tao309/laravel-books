<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\SourceProductUserDatas\SourceProductUserDataResource;
use App\Models\SourceProduct;
use App\Models\SourceProductUserData;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SourceProductUserDataResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves(): void
    {
        $user = User::factory()->create();
        $sp = SourceProduct::factory()->create();
        $spud = SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sp->id,
            SourceProductUserData::PARAM_USER_ID => $user->id,
        ]);

        $data = (new SourceProductUserDataResource($spud))->toArray(new Request);
        $this->assertIsArray($data);
    }
}
