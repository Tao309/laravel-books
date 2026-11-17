<?php

namespace Tests\Feature\Repositories;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use App\Repositories\ProductStockRepository;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ProductStockRepository — только massSave (upsert) и delete,
 * оба скоупятся по Auth::id(). Конфликтный ключ — (product_id, user_id, date),
 * обновляется только qty (log остаётся прежним).
 */
class ProductStockRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductStockRepository $repository;

    private User $user;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(ProductStockRepository::class);
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();

        $this->actingAs($this->user);
    }

    private function stockData(int $qty, ?array $log = null, string $date = '2026-01-01 00:00:00'): array
    {
        return [
            ProductStock::PARAM_QTY => $qty,
            ProductStock::PARAM_DATE => $date,
            ProductStock::PARAM_LOG => $log,
        ];
    }

    public function test_mass_save_inserts_rows_with_current_user(): void
    {
        $result = $this->repository->massSave($this->product->id, [
            $this->stockData(42, date: '2026-01-01 00:00:00'),
            $this->stockData(0, date: '2026-01-02 00:00:00'),
        ]);

        $this->assertTrue($result);
        $this->assertDatabaseHas(ProductStock::TABLE_NAME, [
            ProductStock::PARAM_PRODUCT_ID => $this->product->id,
            ProductStock::PARAM_USER_ID => $this->user->id,
            ProductStock::PARAM_QTY => 42,
        ]);
        $this->assertDatabaseHas(ProductStock::TABLE_NAME, [
            ProductStock::PARAM_PRODUCT_ID => $this->product->id,
            ProductStock::PARAM_USER_ID => $this->user->id,
            ProductStock::PARAM_QTY => 0,
        ]);
    }

    /**
     * Документация дефекта: две строки с одинаковым конфликтным ключом
     * (product_id, user_id, date) в ОДНОМ upsert-батче роняют pgsql —
     * «ON CONFLICT DO UPDATE command cannot affect row a second time»
     * (кардинальное нарушение), массовое сохранение не выполняется вовсе:
     * стейтмент атомарен, ни одна строка не вставлена.
     *
     * Пост-ассерты после пойманного QueryException невозможны: транзакция
     * pgsql абортнута (25P02), любой запрос падает — фиксируем только
     * сам QueryException.
     */
    public function test_mass_save_with_duplicate_keys_in_one_batch_throws_and_inserts_nothing(): void
    {
        $this->expectException(QueryException::class);
        $this->expectExceptionMessage('ON CONFLICT DO UPDATE command cannot affect row a second time');

        $this->repository->massSave($this->product->id, [
            $this->stockData(42),
            $this->stockData(0),
        ]);
    }

    public function test_mass_save_with_empty_array_is_noop(): void
    {
        $result = $this->repository->massSave($this->product->id, []);

        $this->assertTrue($result);
        $this->assertDatabaseCount(ProductStock::TABLE_NAME, 0);
    }

    public function test_mass_save_upserts_qty_on_same_key(): void
    {
        $this->repository->massSave($this->product->id, [
            $this->stockData(42),
        ]);

        $this->repository->massSave($this->product->id, [
            $this->stockData(7),
        ]);

        $this->assertSame(
            1,
            ProductStock::query()->where(ProductStock::PARAM_PRODUCT_ID, $this->product->id)->count(),
        );
        $this->assertDatabaseHas(ProductStock::TABLE_NAME, [
            ProductStock::PARAM_PRODUCT_ID => $this->product->id,
            ProductStock::PARAM_USER_ID => $this->user->id,
            ProductStock::PARAM_QTY => 7,
        ]);
    }

    /**
     * ВАЖНО: log (json) намеренно не гоняется через massSave — upsert
     * идёт мимо Eloquent-кастов, и поведение массива в PDO-биндинге
     * не определено. Каст log покрыт в ProductStocksDatabaseTest и
     * ProductStockResourceTest.
     */
    public function test_mass_save_inserts_null_log(): void
    {
        $this->repository->massSave($this->product->id, [
            $this->stockData(42),
        ]);

        $stock = ProductStock::query()
            ->where(ProductStock::PARAM_PRODUCT_ID, $this->product->id)
            ->firstOrFail();

        $this->assertNull($stock->log);
    }

    public function test_delete_removes_rows_of_current_user(): void
    {
        ProductStock::factory()->count(2)->create([
            ProductStock::PARAM_PRODUCT_ID => $this->product->id,
            ProductStock::PARAM_USER_ID => $this->user->id,
        ]);

        $this->repository->delete($this->product->id);

        $this->assertDatabaseCount(ProductStock::TABLE_NAME, 0);
    }

    public function test_delete_of_other_users_rows_is_silent_noop(): void
    {
        $others = ProductStock::factory()->create([
            ProductStock::PARAM_PRODUCT_ID => $this->product->id,
            ProductStock::PARAM_USER_ID => User::factory()->create()->id,
        ]);

        $this->repository->delete($this->product->id);

        // assertModelExists неприменим: составной PK роняет getKey() TypeError'ом
        $this->assertDatabaseHas(ProductStock::TABLE_NAME, [
            ProductStock::PARAM_PRODUCT_ID => $this->product->id,
            ProductStock::PARAM_USER_ID => $others->user_id,
            ProductStock::PARAM_QTY => $others->qty,
        ]);
    }
}
