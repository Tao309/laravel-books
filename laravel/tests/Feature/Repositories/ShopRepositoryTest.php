<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\Shops\GetShopsQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\DtoInterface;
use App\Models\Shop;
use App\Repositories\ShopRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий Shop — глобальный справочник без скоупов; CRUD работает
 * на уровне репозитория, но create/update/delete через шину недостижимы
 * (ShopProvider не привязывает RepositoryInterface к командным хендлерам).
 * DTO для Shop не существует — getEntityData собирается на лету из констант.
 */
class ShopRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ShopRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(ShopRepository::class);
    }

    /**
     * Штатного ShopDTO нет; интерфейс RepositoryInterface требует DtoInterface,
     * поэтому used-in-repo данные собираются анонимной реализацией.
     */
    private function shopDto(array $data): DtoInterface
    {
        return new class($data) implements DtoInterface
        {
            public function __construct(private readonly array $data) {}

            public static function fromArray(array $data): self
            {
                return new self($data);
            }

            public function getEntityData(): array
            {
                return $this->data;
            }

            public function getRelationsData(): array
            {
                return [];
            }

            public function toArray(): array
            {
                return array_merge($this->data, $this->getRelationsData());
            }
        };
    }

    public function test_create_persists_shop(): void
    {
        $shop = $this->repository->create($this->shopDto([
            Shop::PARAM_TYPE => 'ozon',
            Shop::PARAM_DOMAIN => 'ozon.ru',
            Shop::PARAM_URL => 'https://ozon.ru',
        ]));

        $this->assertDatabaseHas(Shop::TABLE_NAME, [
            Shop::PARAM_ID => $shop->id,
            Shop::PARAM_TYPE => 'ozon',
            Shop::PARAM_DOMAIN => 'ozon.ru',
            Shop::PARAM_URL => 'https://ozon.ru',
        ]);
    }

    public function test_get_returns_shop(): void
    {
        $shop = Shop::factory()->create();

        $this->assertTrue($this->repository->get($shop->id)->is($shop));
    }

    public function test_get_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get(99999);
    }

    /**
     * Документация текущего поведения: getAll() = Shop::all(),
     * фильтры из CollectionQueryDto (limit/search/ids) игнорируются.
     */
    public function test_get_all_ignores_query_filters(): void
    {
        Shop::factory()->count(3)->create();

        $result = $this->repository->getAll(new GetShopsQuery(new CollectionQueryDto(limit: 1)));

        $this->assertCount(3, $result);
    }

    public function test_get_all_paginate_returns_default_paginator(): void
    {
        Shop::factory()->count(20)->create();

        $result = $this->repository->getAllPaginate(new GetShopsQuery(new CollectionQueryDto));

        $this->assertSame(20, $result->total());
        $this->assertSame(15, $result->perPage());
        $this->assertSame(2, $result->lastPage());
    }

    public function test_update_changes_domain(): void
    {
        $shop = Shop::factory()->create();

        $updated = $this->repository->update($shop->id, $this->shopDto([
            Shop::PARAM_TYPE => 'ozon',
            Shop::PARAM_DOMAIN => 'new-domain.ru',
            Shop::PARAM_URL => null,
        ]));

        $this->assertSame('new-domain.ru', $updated->domain);
        $this->assertDatabaseHas(Shop::TABLE_NAME, [
            Shop::PARAM_ID => $shop->id,
            Shop::PARAM_DOMAIN => 'new-domain.ru',
        ]);
    }

    public function test_update_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->update(99999, $this->shopDto([
            Shop::PARAM_TYPE => 'ozon',
            Shop::PARAM_DOMAIN => 'new-domain.ru',
            Shop::PARAM_URL => null,
        ]));
    }

    public function test_delete_removes_shop(): void
    {
        $shop = Shop::factory()->create();

        $this->assertTrue($this->repository->delete($shop->id));
        $this->assertModelMissing($shop);
    }
}
