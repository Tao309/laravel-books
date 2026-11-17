<?php

namespace Tests\Feature\Services;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Services\MassSaveProductsService;
use App\Services\SessionShopService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * MassSaveProductsService::process: чанки по 10 элементов в отдельных
 * транзакциях, create при отсутствии id / update при наличии, агрегаты
 * products_count/errors_count/saved_count/items/error_messages.
 */
class MassSaveProductsServiceTest extends TestCase
{
    use RefreshDatabase;

    private MassSaveProductsService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $shop = Shop::factory()->ozon()->create();

        $this->actingAs(User::factory()->create());

        $this->app->make(SessionShopService::class)->setShop($shop);

        $this->service = $this->app->make(MassSaveProductsService::class);
    }

    private function createItem(string $shopProductId, string $title): array
    {
        return [
            'shop_product_id' => $shopProductId,
            'title' => $title,
            'shop' => ['id' => 1],
        ];
    }

    private function updateItem(int $id, string $shopProductId, string $title): array
    {
        return array_merge($this->createItem($shopProductId, $title), ['id' => $id]);
    }

    public function test_process_creates_and_updates_items(): void
    {
        $existing = Product::factory()->state(fn () => [
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ])->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_SHOP_PRODUCT_ID => '111111111111',
        ]);

        $result = $this->service->process([
            $this->createItem('222222222222', 'Новый товар'),
            $this->updateItem($existing->id, '111111111111', 'Обновлённый товар'),
        ]);

        $this->assertSame(2, $result['products_count']);
        $this->assertSame(0, $result['errors_count']);
        $this->assertSame([], $result['error_messages']);

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_SHOP_PRODUCT_ID => '222222222222',
            Product::PARAM_TITLE => 'Новый товар',
        ]);
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $existing->id,
            Product::PARAM_TITLE => 'Обновлённый товар',
        ]);
    }

    /**
     * Документация дефекта: после DB::commit() внутри цикла идут
     * `$ids = []; $products = [];` — saved_count и items всегда пустые,
     * даже когда все чанки успешны.
     */
    public function test_process_all_chunks_committed_saved_count_still_zero(): void
    {
        $result = $this->service->process([
            $this->createItem('333333333331', 'Товар раз'),
            $this->createItem('333333333332', 'Товар два'),
        ]);

        $this->assertSame(0, $result['saved_count']);
        $this->assertCount(0, $result['items']);

        // успех подтверждаем по БД, а не по payload
        $this->assertDatabaseCount(Product::TABLE_NAME, 2);
    }

    public function test_process_rolls_back_failed_chunk_only(): void
    {
        $items = [];

        foreach (range(1, 10) as $i) {
            $items[] = $this->createItem(
                sprintf('%012d', 400000 + $i),
                "Чанк товар номер {$i}",
            );
        }

        // 11-й элемент — второй чанк: update по несуществующему id
        $items[] = $this->updateItem(99999, '555555555555', 'Товар с ошибкой');

        $result = $this->service->process($items);

        $this->assertSame(11, $result['products_count']);
        $this->assertSame(1, $result['errors_count']);
        $this->assertSame(0, $result['saved_count']);
        $this->assertCount(1, $result['error_messages']);

        foreach (range(1, 10) as $i) {
            $this->assertDatabaseHas(Product::TABLE_NAME, [
                Product::PARAM_SHOP_PRODUCT_ID => sprintf('%012d', 400000 + $i),
                Product::PARAM_TITLE => "Чанк товар номер {$i}",
            ]);
        }

        $this->assertDatabaseMissing(Product::TABLE_NAME, [
            Product::PARAM_TITLE => 'Товар с ошибкой',
        ]);
    }

    public function test_process_with_empty_items_returns_zero_counters(): void
    {
        $result = $this->service->process([]);

        $this->assertSame(0, $result['products_count']);
        $this->assertSame(0, $result['errors_count']);
        $this->assertSame(0, $result['saved_count']);
        $this->assertCount(0, $result['items']);
        $this->assertSame([], $result['error_messages']);
    }
}
