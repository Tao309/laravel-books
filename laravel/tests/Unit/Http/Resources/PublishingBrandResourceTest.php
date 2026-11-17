<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\PublishingBrand\PublishingBrandResource;
use App\Models\PublishingBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * resolve() вызывается с явным Request, чтобы не дергать контейнер
 * (PHPUnit\TestCase без Laravel): ниже по стеку ресурсы контейнер всё равно не нужны.
 */
class PublishingBrandResourceTest extends TestCase
{
    public function test_resolves_id_name_and_timestamps(): void
    {
        $brand = $this->brand();

        $data = PublishingBrandResource::make($brand)->resolve(new Request);

        $this->assertSame(5, $data['id']);
        $this->assertSame('Красная звезда', $data['name']);
        $this->assertSame('2026-11-10 12:00:00', $data['created_at']);
        $this->assertSame('2026-11-11 13:30:00', $data['updated_at']);
    }

    public function test_timestamps_are_null_for_unpersisted_model(): void
    {
        $brand = new PublishingBrand;
        $brand->id = 5;
        $brand->name = 'Красная звезда';

        $data = PublishingBrandResource::make($brand)->resolve(new Request);

        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    private function brand(): PublishingBrand
    {
        $brand = new PublishingBrand;
        $brand->id = 5;
        $brand->name = 'Красная звезда';
        $brand->created_at = Carbon::create(2026, 11, 10, 12, 0, 0);
        $brand->updated_at = Carbon::create(2026, 11, 11, 13, 30, 0);

        return $brand;
    }
}
