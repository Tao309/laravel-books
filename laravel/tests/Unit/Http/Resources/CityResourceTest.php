<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\Cities\CityResource;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * resolve() вызывается с явным Request, чтобы не дергать контейнер
 * (PHPUnit\Framework\TestCase без Laravel).
 */
class CityResourceTest extends TestCase
{
    public function test_resolves_id_code_title_and_timestamps(): void
    {
        $data = CityResource::make($this->city())->resolve(new Request);

        $this->assertSame(5, $data['id']);
        $this->assertSame('KZN', $data['code']);
        $this->assertSame('Казань', $data['title']);
        $this->assertSame('2026-11-10 12:00:00', $data['updated_at']);
        $this->assertSame('2026-11-11 13:30:00', $data['created_at']);
    }

    public function test_timestamps_are_null_for_unpersisted_model(): void
    {
        $city = new City;
        $city->id = 5;
        $city->code = 'KZN';
        $city->title = 'Казань';

        $data = CityResource::make($city)->resolve(new Request);

        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    private function city(): City
    {
        $city = new City;
        $city->id = 5;
        $city->code = 'KZN';
        $city->title = 'Казань';
        $city->created_at = Carbon::create(2026, 11, 11, 13, 30, 0);
        $city->updated_at = Carbon::create(2026, 11, 10, 12, 0, 0);

        return $city;
    }
}
