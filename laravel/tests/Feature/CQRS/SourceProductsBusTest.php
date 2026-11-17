<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\SourceProducts\CreateSourceProductCommand;
use App\CQRS\Commands\SourceProducts\DestroySourceProductCommand;
use App\CQRS\Commands\SourceProducts\UpdateSourceProductCommand;
use App\CQRS\Queries\SourceProducts\GetSourceProductByIdSQuery;
use App\CQRS\Queries\SourceProducts\GetSourceProductsPaginateQuery;
use App\CQRS\Queries\SourceProducts\GetSourceProductsQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\SourceProductDTO;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Сквозной путь команды/запроса через реальный контейнер и репозиторий.
 * Пагинат-запрос маппится в существующий GetSourceProductsPaginateHandler,
 * но SourceProductProvider не привязывает к нему RepositoryInterface →
 * BindingResolutionException (роуты используют невырожденный index).
 */
class SourceProductsBusTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = $this->app->make(Bus::class);
        $this->user = User::factory()->create();

        $this->actingAs($this->user);
    }

    private function dto(SourceProductType $type, array $overrides = []): SourceProductDTO
    {
        return SourceProductDTO::fromArray(array_merge([
            SourceProduct::PARAM_TITLE => 'Кроссовки лето',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => ['id' => $type->id],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [],
        ], $overrides));
    }

    public function test_create_command_persists_source_product(): void
    {
        $type = SourceProductType::factory()->create();

        $sourceProduct = $this->bus->handle(new CreateSourceProductCommand($this->dto($type)));

        $this->assertInstanceOf(SourceProduct::class, $sourceProduct);
        $this->assertDatabaseHas(SourceProduct::TABLE_NAME, [
            SourceProduct::PARAM_ID => $sourceProduct->id,
            SourceProduct::PARAM_TITLE => 'Кроссовки лето',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
            SourceProduct::PARAM_USER_CREATED_ID => $this->user->id,
        ]);
    }

    public function test_update_command_changes_title(): void
    {
        $type = SourceProductType::factory()->create();
        $sourceProduct = SourceProduct::factory()->create([
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);

        $updated = $this->bus->handle(new UpdateSourceProductCommand(
            $sourceProduct->id,
            $this->dto($type, [SourceProduct::PARAM_TITLE => 'Куртка осенняя']),
        ));

        $this->assertSame('Куртка осенняя', $updated->title);
    }

    public function test_destroy_command_deletes_source_product(): void
    {
        $sourceProduct = SourceProduct::factory()->create();

        $this->assertTrue($this->bus->handle(new DestroySourceProductCommand($sourceProduct->id)));
        $this->assertModelMissing($sourceProduct);
    }

    public function test_destroy_command_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new DestroySourceProductCommand(99999));
    }

    public function test_get_products_query_returns_all(): void
    {
        SourceProduct::factory()->count(3)->create();

        $result = $this->bus->handle(new GetSourceProductsQuery(new CollectionQueryDto));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_get_by_id_query_returns_model(): void
    {
        $sourceProduct = SourceProduct::factory()->create();

        $this->assertTrue(
            $this->bus->handle(new GetSourceProductByIdSQuery($sourceProduct->id))->is($sourceProduct),
        );
    }

    public function test_get_by_id_query_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new GetSourceProductByIdSQuery(99999));
    }

    public function test_get_products_paginate_query_fails_without_repository_binding(): void
    {
        SourceProduct::factory()->count(3)->create();

        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\SourceProducts\GetSourceProductsPaginateHandler].',
        );

        $this->bus->handle(new GetSourceProductsPaginateQuery(new CollectionQueryDto));
    }
}
