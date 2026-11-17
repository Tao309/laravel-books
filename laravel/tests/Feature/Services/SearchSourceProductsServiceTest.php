<?php

namespace Tests\Feature\Services;

use App\DTOs\CollectionQueryDto;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Models\User;
use App\Services\SearchSourceProductsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SearchSourceProductsService: searchByValue → Bus →
 * GetSourceProductsQuery → SourceProductRepository::getAll.
 * Bus объявлен final readonly, мокать нельзя — wiring проверяется
 * в реальном режиме с БД.
 */
class SearchSourceProductsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Shop::factory()->ozon()->create();
        $this->actingAs(User::factory()->create());
    }

    /**
     * SearchSourceProductsService зовёт GetSourceProductsQuery (без пагинации):
     * наружу уходит Eloquent Collection (с лимитом 7 из поискового фильтра),
     * а не LengthAwarePaginator.
     */
    public function test_search_by_value_returns_matching_source_products_collection(): void
    {
        SourceProduct::factory()->create([
            SourceProduct::PARAM_TITLE => 'Специальный товар для поиска',
        ]);
        SourceProduct::factory()->create([
            SourceProduct::PARAM_TITLE => 'Совершенно иной товар',
        ]);

        $service = $this->app->make(SearchSourceProductsService::class);

        $result = $service->searchByValue(new CollectionQueryDto(search: 'специальный'));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
        $this->assertSame(
            'Специальный товар для поиска',
            $result->first()->{SourceProduct::PARAM_TITLE},
        );
    }
}
