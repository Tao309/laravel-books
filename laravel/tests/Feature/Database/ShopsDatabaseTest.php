<?php

namespace Tests\Feature\Database;

use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: структура таблицы shops на реальном pgsql.
 * Уникальности нет ни на type, ни на domain — фиксируется поведением.
 */
class ShopsDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(Shop::TABLE_NAME);

        $this->assertContains(Shop::PARAM_ID, $columns);
        $this->assertContains(Shop::PARAM_TYPE, $columns);
        $this->assertContains(Shop::PARAM_DOMAIN, $columns);
        $this->assertContains(Shop::PARAM_URL, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_type_and_domain_columns_are_strings(): void
    {
        // pgsql возвращает native doctrine-типы: varchar, а не string
        $this->assertSame(
            'varchar',
            Schema::getColumnType(Shop::TABLE_NAME, Shop::PARAM_TYPE),
        );
        $this->assertSame(
            'varchar',
            Schema::getColumnType(Shop::TABLE_NAME, Shop::PARAM_DOMAIN),
        );
    }

    public function test_url_is_nullable_in_database(): void
    {
        $shop = Shop::factory()->create([
            Shop::PARAM_TYPE => 'ozon',
            Shop::PARAM_DOMAIN => 'ozon.ru',
            Shop::PARAM_URL => null,
        ]);

        $this->assertNull($shop->url);
        $this->assertModelExists($shop);
    }

    public function test_database_does_not_enforce_unique_type(): void
    {
        $first = Shop::factory()->create([Shop::PARAM_TYPE => 'ozon']);
        $second = Shop::factory()->create([Shop::PARAM_TYPE => 'ozon']);

        $this->assertNotSame($first->id, $second->id);
        $this->assertSame(2, Shop::query()->count());
    }

    public function test_named_states_create_deterministic_shops(): void
    {
        $ozon = Shop::factory()->ozon()->create();
        $wb = Shop::factory()->wb()->create();
        $chitaiGorod = Shop::factory()->chitaiGorod()->create();

        $this->assertSame('ozon', $ozon->type);
        $this->assertSame('wildberries', $wb->type);
        $this->assertSame('chitai-gorod', $chitaiGorod->type);
        $this->assertSame(3, Shop::query()->count());
    }
}
