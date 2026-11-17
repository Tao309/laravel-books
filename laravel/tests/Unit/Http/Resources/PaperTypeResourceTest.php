<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\PaperTypes\PaperTypeResource;
use App\Models\PaperType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * resolve() вызывается с явным Request, чтобы не дергать контейнер
 * (PHPUnit\Framework\TestCase без Laravel).
 */
class PaperTypeResourceTest extends TestCase
{
    public function test_resolves_id_label_description_and_timestamps(): void
    {
        $data = PaperTypeResource::make($this->paperType())->resolve(new Request);

        $this->assertSame(5, $data['id']);
        $this->assertSame('Мелованная', $data['label']);
        $this->assertSame('Бумага с мелованным покрытием', $data['description']);
        $this->assertSame('2026-11-10 12:00:00', $data['updated_at']);
        $this->assertSame('2026-11-11 13:30:00', $data['created_at']);
    }

    public function test_description_is_null_when_not_set(): void
    {
        $paperType = new PaperType;
        $paperType->id = 5;
        $paperType->label = 'Мелованная';

        $data = PaperTypeResource::make($paperType)->resolve(new Request);

        $this->assertNull($data['description']);
    }

    public function test_timestamps_are_null_for_unpersisted_model(): void
    {
        $paperType = new PaperType;
        $paperType->id = 5;
        $paperType->label = 'Мелованная';

        $data = PaperTypeResource::make($paperType)->resolve(new Request);

        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    private function paperType(): PaperType
    {
        $paperType = new PaperType;
        $paperType->id = 5;
        $paperType->label = 'Мелованная';
        $paperType->description = 'Бумага с мелованным покрытием';
        $paperType->created_at = Carbon::create(2026, 11, 11, 13, 30, 0);
        $paperType->updated_at = Carbon::create(2026, 11, 10, 12, 0, 0);

        return $paperType;
    }
}
