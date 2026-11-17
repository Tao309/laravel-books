<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\SourceProductTypes\SourceProductTypeResource;
use App\Models\SourceProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * resolve() вызывается с явным Request, чтобы не дергать контейнер
 * (PHPUnit\Framework\TestCase без Laravel).
 */
class SourceProductTypeResourceTest extends TestCase
{
    public function test_resolves_id_code_name_and_timestamps(): void
    {
        $data = SourceProductTypeResource::make($this->type())->resolve(new Request);

        $this->assertSame(5, $data['id']);
        $this->assertSame('shoes', $data['code']);
        $this->assertSame('Обувь', $data['name']);
        $this->assertSame('2026-11-10 12:00:00', $data['updated_at']);
        $this->assertSame('2026-11-11 13:30:00', $data['created_at']);
    }

    public function test_timestamps_are_null_for_unpersisted_model(): void
    {
        $type = new SourceProductType;
        $type->id = 5;
        $type->code = 'shoes';
        $type->name = 'Обувь';

        $data = SourceProductTypeResource::make($type)->resolve(new Request);

        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    private function type(): SourceProductType
    {
        $type = new SourceProductType;
        $type->id = 5;
        $type->code = 'shoes';
        $type->name = 'Обувь';
        $type->created_at = Carbon::create(2026, 11, 11, 13, 30, 0);
        $type->updated_at = Carbon::create(2026, 11, 10, 12, 0, 0);

        return $type;
    }
}
