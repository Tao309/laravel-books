<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\PublishingHouses\PublishingHouseResource;
use App\Models\PublishingHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * resolve() вызывается с явным Request, чтобы не дергать контейнер
 * (PHPUnit\Framework\TestCase без Laravel).
 */
class PublishingHouseResourceTest extends TestCase
{
    public function test_resolves_id_name_and_timestamps(): void
    {
        $data = PublishingHouseResource::make($this->house())->resolve(new Request);

        $this->assertSame(5, $data['id']);
        $this->assertSame('Полярная звезда', $data['name']);
        $this->assertSame('2026-11-10 12:00:00', $data['created_at']);
        $this->assertSame('2026-11-11 13:30:00', $data['updated_at']);
    }

    public function test_timestamps_are_null_for_unpersisted_model(): void
    {
        $house = new PublishingHouse;
        $house->id = 5;
        $house->name = 'Полярная звезда';

        $data = PublishingHouseResource::make($house)->resolve(new Request);

        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    private function house(): PublishingHouse
    {
        $house = new PublishingHouse;
        $house->id = 5;
        $house->name = 'Полярная звезда';
        $house->created_at = Carbon::create(2026, 11, 10, 12, 0, 0);
        $house->updated_at = Carbon::create(2026, 11, 11, 13, 30, 0);

        return $house;
    }
}
