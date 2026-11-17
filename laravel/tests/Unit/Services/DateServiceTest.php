<?php

namespace Tests\Unit\Services;

use App\Services\DateService;
use Tests\TestCase;

/**
 * Чистые unit-тесты DateService: сдвиг времени, таймзона, нормализация форматов.
 * БД не используется.
 */
class DateServiceTest extends TestCase
{
    /**
     * Документация дефекта: при дефолтном TZ=UTC модификация '-3 hours'
     * ровно компенсирует переметку зоны (UTC → Europe/Moscow) — wall-time
     * входной строки не меняется, т.е. строка трактуется как московское
     * время; timestamp уходит на -3 часа от UTC-парсинга входа.
     */
    public function test_get_date_time_treats_input_as_moscow_wall_time(): void
    {
        $dateTime = DateService::getDateTime('2026-11-17 15:00:00');

        $this->assertSame('2026-11-17 15:00:00', $dateTime->format('Y-m-d H:i:s'));
        $this->assertSame('Europe/Moscow', $dateTime->getTimezone()->getName());
        $this->assertSame(
            gmmktime(12, 0, 0, 11, 17, 2026), // 15:00 UTC минус 3 часа
            $dateTime->getTimestamp(),
        );
    }

    public function test_get_date_time_uses_moscow_timezone(): void
    {
        $dateTime = DateService::getDateTime('2026-11-17 15:00:00');

        $this->assertSame('Europe/Moscow', $dateTime->getTimezone()->getName());
    }

    /**
     * Документация дефекта: getDateTime(null) → new DateTime(null) — это
     * «сейчас» (а не ошибка/null). Пара «сегодняшняя дата - 3 часа» уходит
     * в вчерашний день после полуночи по МСК.
     */
    public function test_get_date_time_with_null_returns_now_minus_three_hours(): void
    {
        $before = time();
        $dateTime = DateService::getDateTime(null);
        $after = time();

        $this->assertGreaterThanOrEqual($before - 3 * 3600, $dateTime->getTimestamp());
        $this->assertLessThanOrEqual($after - 3 * 3600, $dateTime->getTimestamp());
    }

    public function test_get_current_date_time_zone_is_moscow(): void
    {
        $this->assertSame('Europe/Moscow', DateService::getCurrentDateTimeZone()->getName());
    }

    /**
     * Документация дефекта: DateTime::createFromFormat добирает отсутствующие
     * в формате компоненты времени из текущего момента — для 'Y.m.d'
     * получаем сегодняшние часы/минуты/секунды, а не 00:00:00. Нормализуется
     * только дата-часть.
     */
    public function test_create_from_date_normalizes_date_part_to_sql_format(): void
    {
        $result = DateService::createFromDate('2026.11.01', 'Y.m.d');

        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $result);
        $this->assertSame('2026-11-01', substr($result, 0, 10));

        $this->assertSame(
            '2026-11-01 10:30:00',
            DateService::createFromDate('2026-11-01 10:30:00'),
        );
    }

    public function test_create_from_date_with_null_returns_null(): void
    {
        $this->assertNull(DateService::createFromDate(null));
    }
}
