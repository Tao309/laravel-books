<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\SourceProducts\GetSourceProductsQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\SourceProductDTO;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use App\Models\User;
use App\Repositories\SourceProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий SourceProduct единственный из пачки работает с транзакцией,
 * текущим пользователем и вспомогательной таблицей source_product_user_data.
 */
class SourceProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private SourceProductRepository $repository;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(SourceProductRepository::class);
        $this->user = User::factory()->create();

        // create/update берут Auth::id()
        $this->actingAs($this->user);
    }

    private function dto(array $overrides = []): SourceProductDTO
    {
        // ����������������� ���: random word ������� ����������� ��
        // source_product_types_code_unique ��� 2+ ����� � ����� �����.
        $type = SourceProductType::factory()->shoes()->create();

        return SourceProductDTO::fromArray(array_merge([
            SourceProduct::PARAM_TITLE => 'Кроссовки лето',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => ['id' => $type->id],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                SourceProductUserData::PARAM_COMMENT => 'Нужен размер 43',
                SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
            ],
        ], $overrides));
    }

    public function test_create_persists_source_product_with_type_user_and_user_data(): void
    {
        $type = SourceProductType::factory()->shirts()->create();
        $dto = $this->dto([
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => ['id' => $type->id],
        ]);

        $sourceProduct = $this->repository->create($dto);

        $this->assertDatabaseHas(SourceProduct::TABLE_NAME, [
            SourceProduct::PARAM_ID => $sourceProduct->id,
            SourceProduct::PARAM_TITLE => 'Кроссовки лето',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
            SourceProduct::PARAM_USER_CREATED_ID => $this->user->id,
        ]);

        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
            SourceProductUserData::PARAM_COMMENT => 'Нужен размер 43',
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
        ]);
    }

    /**
     * Документация текущего поведения: user_data создаётся всегда,
     * даже если блок source_product_user_data не передан (null-значения).
     */
    public function test_create_without_user_data_creates_empty_user_data_row(): void
    {
        $dto = $this->dto([
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [],
        ]);

        $sourceProduct = $this->repository->create($dto);

        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
            SourceProductUserData::PARAM_COMMENT => null,
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => null,
        ]);
    }

    public function test_get_returns_source_product_with_eager_relations(): void
    {
        $sourceProduct = SourceProduct::factory()->create();

        $found = $this->repository->get($sourceProduct->id);

        $this->assertTrue($found->is($sourceProduct));
        $this->assertTrue($found->relationLoaded('sourceProductType'));
        $this->assertTrue($found->relationLoaded('userCreated'));
    }

    public function test_get_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get(99999);
    }

    /**
     * Один общий тип на тест: definition SourceProductFactory создаёт
     * nested SourceProductType::factory() на каждый товар, random word
     * коллизирует по source_product_types_code_unique при 2+ товарах.
     */
    private function type(): SourceProductType
    {
        return SourceProductType::factory()->medicines()->create();
    }

    public function test_get_all_with_search_matches_title_variants(): void
    {
        $type = $this->type();
        $found = SourceProduct::factory()->create([
            SourceProduct::PARAM_TITLE => 'Кроссовки лето',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);
        SourceProduct::factory()->create([
            SourceProduct::PARAM_TITLE => 'Платье зимнее',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);

        // applyTitleSearchFilter: точное совпадение, префиксы, первые два слова
        $result = $this->repository->getAll(new GetSourceProductsQuery(new CollectionQueryDto(search: 'кроссовки лето')));

        $this->assertTrue($result->contains($found));
        $this->assertFalse($result->contains(
            SourceProduct::query()->where(SourceProduct::PARAM_TITLE, 'Платье зимнее')->first(),
        ));
    }

    /**
     * Документация дефекта: AbstractQueryFilter::apply вызывает
     * ->when($dto->getLimit()) ПОСЛЕ search-фильтра, а CollectionQueryDto
     * дефолтит limit = 30 — limit(7) из applyTitleSearchFilter каждый раз
     * перезаписывается. Реальный лимит живёт на уровне request/API,
     * репозиторий возвращает все совпадения.
     */
    public function test_get_all_with_search_limits_to_seven(): void
    {
        $type = $this->type();

        foreach (range(1, 10) as $i) {
            SourceProduct::factory()->create([
                SourceProduct::PARAM_TITLE => "Джинсы модель {$i}",
                SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
            ]);
        }

        $result = $this->repository->getAll(new GetSourceProductsQuery(new CollectionQueryDto(search: 'джинсы')));

        $this->assertCount(10, $result);
    }

    /**
     * Документация дефекта: AbstractQueryFilter::applyIdsFilter строит
     * whereIn($query->getModel()->getKey(), ...) — getKey() возвращает
     * ЗНАЧЕНИЕ ключа несохранённой модели (null), а не имя колонки:
     * SQL «where "" in (...)» и pgsql роняет 42601 (zero-length identifier).
     */
    public function test_get_all_applies_ids_filter(): void
    {
        $type = $this->type();
        $first = SourceProduct::factory()->create([SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id]);
        SourceProduct::factory()->create([SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id]);
        $third = SourceProduct::factory()->create([SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id]);

        $this->expectException(QueryException::class);

        $this->repository->getAll(new GetSourceProductsQuery(new CollectionQueryDto(ids: [$first->id, $third->id])));
    }

    public function test_get_all_paginate_returns_default_paginator(): void
    {
        $type = $this->type();

        SourceProduct::factory()->count(20)->create([
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);

        $result = $this->repository->getAllPaginate(new GetSourceProductsQuery(new CollectionQueryDto));

        $this->assertSame(20, $result->total());
        $this->assertSame(15, $result->perPage());
        $this->assertSame(2, $result->lastPage());
    }

    public function test_update_changes_title_type_and_user_data(): void
    {
        $oldType = SourceProductType::factory()->beverages()->create();
        $newType = SourceProductType::factory()->food()->create();
        $sourceProduct = SourceProduct::factory()->create([
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $oldType->id,
        ]);

        $dto = $this->dto([
            SourceProduct::PARAM_TITLE => 'Куртка осенняя',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => ['id' => $newType->id],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                SourceProductUserData::PARAM_COMMENT => 'Обновлённый комментарий',
            ],
        ]);

        $updated = $this->repository->update($sourceProduct->id, $dto);

        $this->assertSame('Куртка осенняя', $updated->title);
        $this->assertSame($newType->id, $updated->source_product_type_id);
        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
            SourceProductUserData::PARAM_COMMENT => 'Обновлённый комментарий',
        ]);
    }

    public function test_update_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->update(99999, $this->dto());
    }

    public function test_delete_removes_source_product_and_cascades_user_data(): void
    {
        $sourceProduct = SourceProduct::factory()->create();

        $this->assertTrue($this->repository->delete($sourceProduct->id));
        $this->assertModelMissing($sourceProduct);
        $this->assertSame(
            0,
            SourceProductUserData::query()->where(
                SourceProductUserData::PARAM_SOURCE_PRODUCT_ID,
                $sourceProduct->id,
            )->count(),
        );
    }
}
