<?php

namespace Tests\Unit\Services;

use App\Services\DateService;
use Carbon\Exceptions\InvalidFormatException;
use Tests\TestCase;

class DateServiceTest extends TestCase
{
    public function test_returns_null_for_null(): void
    {
        $this->assertNull(DateService::createFromDate(null));
    }

    public function test_returns_null_for_empty_string(): void
    {
        $this->assertNull(DateService::createFromDate(''));
    }

    public function test_normalizes_sql_datetime(): void
    {
        $this->assertSame('2026-11-01 10:30:00', DateService::createFromDate('2026-11-01 10:30:00'));
    }

    public function test_normalizes_dotted_date(): void
    {
        $result = DateService::createFromDate('2026.11.01');
        $this->assertMatchesRegularExpression('/^2026-11-01 \d{2}:\d{2}:\d{2}$/', (string) $result);
    }

    public function test_normalizes_date_only(): void
    {
        $result = DateService::createFromDate('2026-11-01');
        $this->assertMatchesRegularExpression('/^2026-11-01 \d{2}:\d{2}:\d{2}$/', (string) $result);
    }

    public function test_throws_for_invalid_date(): void
    {
        $this->expectException(InvalidFormatException::class);
        DateService::createFromDate('not-a-date');
    }
}
