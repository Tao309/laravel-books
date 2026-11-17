<?php

namespace Tests\Feature\Services;

use App\DTOs\CollectionQueryDto;
use App\Models\Author;
use App\Models\Shop;
use App\Models\User;
use App\Services\SearchAuthorsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

/**
 * SearchAuthorsService: searchByValue → Bus → GetAuthorsPaginateQuery →
 * AuthorRepository::getAllPaginate. Bus объявлен final readonly, мокать
 * нельзя — wiring проверяется в реальном режиме с БД.
 */
class SearchAuthorsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Shop::factory()->ozon()->create();
        $this->actingAs(User::factory()->create());
    }

    public function test_search_by_value_returns_paginated_matching_authors(): void
    {
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Толстой',
        ]);
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Иван',
            Author::PARAM_LAST_NAME => 'Бунин',
        ]);

        $service = $this->app->make(SearchAuthorsService::class);

        $result = $service->searchByValue(new CollectionQueryDto(search: 'лев толстой'));

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertSame(1, $result->total());
        $this->assertSame(
            'Толстой',
            $result->getCollection()->first()->{Author::PARAM_LAST_NAME},
        );
    }
}
