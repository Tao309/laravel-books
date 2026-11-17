<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\BookSeries\CreateBookSeriesCommand;
use App\CQRS\Commands\BookSeries\DestroyBookSeriesCommand;
use App\CQRS\Commands\BookSeries\UpdateBookSeriesCommand;
use App\CQRS\Queries\BookSeries\GetBookSeriesByIdQuery;
use App\CQRS\Queries\BookSeries\GetBookSeriesQuery;
use App\DTOs\BookSeriesDTO;
use App\DTOs\CollectionQueryDto;
use App\Exceptions\HandlerNotFoundException;
use App\Models\BookSeries;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Сквозной путь команды/запроса через реальный контейнер и репозиторий.
 *
 * Для BookSeries destroy-роут исключён, а слой удаления в шине сломан:
 * DestroyBookSeriesCommand → HandlerNotFoundException (хендлера нет).
 * Удаление работает только через BookSeriesRepository::delete.
 */
class BookSeriesBusTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = $this->app->make(Bus::class);
    }

    public function test_create_command_persists_series(): void
    {
        $series = $this->bus->handle(new CreateBookSeriesCommand(
            new BookSeriesDTO('Мировая классика'),
        ));

        $this->assertInstanceOf(BookSeries::class, $series);
        $this->assertDatabaseHas(BookSeries::TABLE_NAME, [
            BookSeries::PARAM_ID => $series->id,
            BookSeries::PARAM_NAME => 'Мировая классика',
        ]);
    }

    public function test_update_command_changes_name(): void
    {
        $series = BookSeries::factory()->create();

        $updated = $this->bus->handle(new UpdateBookSeriesCommand(
            $series->id,
            new BookSeriesDTO('Зарубежная классика'),
        ));

        $this->assertSame('Зарубежная классика', $updated->name);
    }

    public function test_destroy_command_fails_with_handler_not_found(): void
    {
        $series = BookSeries::factory()->create();

        $this->expectException(HandlerNotFoundException::class);

        $this->bus->handle(new DestroyBookSeriesCommand($series->id));
    }

    public function test_get_series_query_returns_all(): void
    {
        BookSeries::factory()->count(3)->create();

        $result = $this->bus->handle(new GetBookSeriesQuery(new CollectionQueryDto));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_get_series_by_id_query_returns_model(): void
    {
        $series = BookSeries::factory()->create();

        $this->assertTrue(
            $this->bus->handle(new GetBookSeriesByIdQuery($series->id))->is($series),
        );
    }

    public function test_get_series_by_id_query_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new GetBookSeriesByIdQuery(99999));
    }
}
