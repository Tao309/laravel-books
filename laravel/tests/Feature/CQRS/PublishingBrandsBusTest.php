<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\PublishingBrands\CreatePublishingBrandCommand;
use App\CQRS\Commands\PublishingBrands\DestroyPublishingBrandCommand;
use App\CQRS\Commands\PublishingBrands\UpdatePublishingBrandCommand;
use App\CQRS\Queries\PublishingBrands\GetPublishingBrandByIdQuery;
use App\CQRS\Queries\PublishingBrands\GetPublishingBrandsQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\PublishingBrandDTO;
use App\Models\PublishingBrand;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Сквозной путь команды/запроса через реальный контейнер и репозиторий.
 * Для PublishingBrand это единственный исполняемый слой удаления:
 * API-роут destroy отсутствует (routes/api.php исключил его).
 */
class PublishingBrandsBusTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = $this->app->make(Bus::class);
    }

    public function test_create_command_persists_brand(): void
    {
        $brand = $this->bus->handle(new CreatePublishingBrandCommand(
            new PublishingBrandDTO('Красная звезда'),
        ));

        $this->assertInstanceOf(PublishingBrand::class, $brand);
        $this->assertDatabaseHas(PublishingBrand::TABLE_NAME, [
            PublishingBrand::PARAM_ID => $brand->id,
            PublishingBrand::PARAM_NAME => 'Красная звезда',
        ]);
    }

    public function test_update_command_changes_name(): void
    {
        $brand = PublishingBrand::factory()->create();

        $updated = $this->bus->handle(new UpdatePublishingBrandCommand(
            $brand->id,
            new PublishingBrandDTO('Серебряная луна'),
        ));

        $this->assertSame('Серебряная луна', $updated->name);
    }

    /**
     * Документация дефекта: DestroyPublishingBrandHandler не привязан
     * к RepositoryInterface в PublishingBrandProvider — контейнер падает
     * с BindingResolutionException раньше любых действий, запись не удаляется.
     */
    public function test_destroy_command_fails_and_keeps_brand(): void
    {
        $brand = PublishingBrand::factory()->create();

        try {
            $this->bus->handle(new DestroyPublishingBrandCommand($brand->id));
            $this->fail('Expected BindingResolutionException');
        } catch (BindingResolutionException) {
            // ожидаемое поведение
        }

        $this->assertModelExists($brand);
    }

    public function test_destroy_command_fails_without_repository_binding(): void
    {
        // Хендлер DestroyPublishingBrandHandler не привязан к RepositoryInterface
        // в PublishingBrandProvider (привязаны только Create/Get/GetById/Update),
        // поэтому контейнер падает раньше, чем репозиторий бросит ModelNotFound.
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\PublishingBrands\DestroyPublishingBrandHandler].',
        );

        $this->bus->handle(new DestroyPublishingBrandCommand(99999));
    }

    public function test_get_brands_query_returns_all(): void
    {
        PublishingBrand::factory()->count(3)->create();

        $result = $this->bus->handle(new GetPublishingBrandsQuery(new CollectionQueryDto));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_get_brand_by_id_query_returns_model(): void
    {
        $brand = PublishingBrand::factory()->create();

        $this->assertTrue(
            $this->bus->handle(new GetPublishingBrandByIdQuery($brand->id))->is($brand),
        );
    }

    public function test_get_brand_by_id_query_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new GetPublishingBrandByIdQuery(99999));
    }
}
