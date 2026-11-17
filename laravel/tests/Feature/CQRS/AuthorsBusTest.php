<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\Authors\CreateAuthorCommand;
use App\CQRS\Commands\Authors\DestroyAuthorCommand;
use App\CQRS\Commands\Authors\UpdateAuthorCommand;
use App\CQRS\Queries\Authors\GetAuthorByIdQuery;
use App\CQRS\Queries\Authors\GetAuthorsPaginateQuery;
use App\DTOs\AuthorDTO;
use App\DTOs\CollectionQueryDto;
use App\Models\Author;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Сквозной путь команды/запроса через реальный контейнер и репозиторий.
 * У Author полный набор привязок в AuthorProvider, включая Destroy —
 * destroy-роут жив и работает (в отличие от publishing_brands/houses).
 */
class AuthorsBusTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = $this->app->make(Bus::class);
    }

    public function test_create_command_persists_author(): void
    {
        $author = $this->bus->handle(new CreateAuthorCommand(
            new AuthorDTO('Лев', 'Толстой'),
        ));

        $this->assertInstanceOf(Author::class, $author);
        $this->assertDatabaseHas(Author::TABLE_NAME, [
            Author::PARAM_ID => $author->id,
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Толстой',
        ]);
    }

    public function test_update_command_changes_names(): void
    {
        $author = Author::factory()->create();

        $updated = $this->bus->handle(new UpdateAuthorCommand(
            $author->id,
            new AuthorDTO('Антон', 'Чехов'),
        ));

        $this->assertSame('Антон', $updated->first_name);
        $this->assertSame('Чехов', $updated->last_name);
    }

    public function test_destroy_command_deletes_author(): void
    {
        $author = Author::factory()->create();

        $this->assertTrue($this->bus->handle(new DestroyAuthorCommand($author->id)));
        $this->assertModelMissing($author);
    }

    public function test_destroy_command_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new DestroyAuthorCommand(99999));
    }

    public function test_get_paginate_query_returns_paginator_with_per_page_100(): void
    {
        Author::factory()->count(20)->create();

        $result = $this->bus->handle(new GetAuthorsPaginateQuery(new CollectionQueryDto));

        $this->assertSame(20, $result->total());
        $this->assertSame(100, $result->perPage());
    }

    public function test_get_by_id_query_returns_model(): void
    {
        $author = Author::factory()->create();

        $this->assertTrue(
            $this->bus->handle(new GetAuthorByIdQuery($author->id))->is($author),
        );
    }

    public function test_get_by_id_query_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new GetAuthorByIdQuery(99999));
    }
}
