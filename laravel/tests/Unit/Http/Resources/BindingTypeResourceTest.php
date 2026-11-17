<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\BindingTypes\BindingTypeResource;
use App\Models\BindingType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * resolve() вызывается с явным Request, чтобы не дергать контейнер
 * (PHPUnit\Framework\TestCase без Laravel).
 */
class BindingTypeResourceTest extends TestCase
{
    public function test_resolves_id_label_and_timestamps(): void
    {
        $data = BindingTypeResource::make($this->bindingType())->resolve(new Request);

        $this->assertSame(5, $data['id']);
        $this->assertSame('Твёрдый переплёт', $data['label']);
        $this->assertSame('2026-11-10 12:00:00', $data['updated_at']);
        $this->assertSame('2026-11-11 13:30:00', $data['created_at']);
    }

    public function test_timestamps_are_null_for_unpersisted_model(): void
    {
        $bindingType = new BindingType;
        $bindingType->id = 5;
        $bindingType->label = 'Твёрдый переплёт';

        $data = BindingTypeResource::make($bindingType)->resolve(new Request);

        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    private function bindingType(): BindingType
    {
        $bindingType = new BindingType;
        $bindingType->id = 5;
        $bindingType->label = 'Твёрдый переплёт';
        $bindingType->created_at = Carbon::create(2026, 11, 11, 13, 30, 0);
        $bindingType->updated_at = Carbon::create(2026, 11, 10, 12, 0, 0);

        return $bindingType;
    }
}
