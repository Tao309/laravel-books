<?php

namespace Tests\Feature\Services;

use App\DTOs\CollectionQueryDto;
use App\Models\Book;
use App\Models\Shop;
use App\Models\User;
use App\Services\SearchBooksService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SearchBooksService: searchByValue → Bus → GetBooksQuery →
 * BookRepository::getAll. Bus объявлен final readonly, мокать
 * нельзя — wiring проверяется в реальном режиме с БД.
 */
class SearchBooksServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Shop::factory()->ozon()->create();
        $this->actingAs(User::factory()->create());
    }

    /**
     * SearchBooksService зовёт GetBooksQuery (без пагинации), хотя существует
     * GetBooksPaginateQuery: наружу уходит Eloquent Collection (с лимитом 7
     * из поискового фильтра), а не LengthAwarePaginator.
     */
    public function test_search_by_value_returns_matching_books_collection(): void
    {
        Book::factory()->create([Book::PARAM_TITLE => 'Специальная книга для поиска']);
        Book::factory()->create([Book::PARAM_TITLE => 'Совершенно иная книга']);

        $service = $this->app->make(SearchBooksService::class);

        $result = $service->searchByValue(new CollectionQueryDto(search: 'специальная'));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
        $this->assertSame(
            'Специальная книга для поиска',
            $result->first()->{Book::PARAM_TITLE},
        );
    }
}
