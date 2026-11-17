<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\BookSeries\BookSeriesResource;
use App\Models\BookSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * resolve() вызывается с явным Request, чтобы не дергать контейнер
 * (PHPUnit\Framework\TestCase без Laravel).
 */
class BookSeriesResourceTest extends TestCase
{
    public function test_resolves_id_name_and_timestamps(): void
    {
        $data = BookSeriesResource::make($this->series())->resolve(new Request);

        $this->assertSame(5, $data['id']);
        $this->assertSame('Мировая классика', $data['name']);
        $this->assertSame('2026-11-10 12:00:00', $data['updated_at']);
        $this->assertSame('2026-11-11 13:30:00', $data['created_at']);
    }

    public function test_timestamps_are_null_for_unpersisted_model(): void
    {
        $series = new BookSeries;
        $series->id = 5;
        $series->name = 'Мировая классика';

        $data = BookSeriesResource::make($series)->resolve(new Request);

        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    private function series(): BookSeries
    {
        $series = new BookSeries;
        $series->id = 5;
        $series->name = 'Мировая классика';
        $series->created_at = Carbon::create(2026, 11, 11, 13, 30, 0);
        $series->updated_at = Carbon::create(2026, 11, 10, 12, 0, 0);

        return $series;
    }
}
