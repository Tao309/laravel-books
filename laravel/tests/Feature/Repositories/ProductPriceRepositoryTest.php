<?php

namespace Tests\Feature\Repositories;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\User;
use App\Repositories\ProductPriceRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ProductPriceRepository — только massSave (upsert) и delete,
 * оба скоупятся по Auth::id(). Конфликтный ключ — (product_id, user_id, date),
 * обновляется только price.
 */
class ProductPriceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductPriceRepository $repository;

    private User $user;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(ProductPriceRepository::class);
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();

        $this->actingAs($this->user);
    }

    public function test_mass_save_inserts_rows_with_current_user(): void
    {
        $result = $this->repository->massSave($this->product->id, [
            [ProductPrice::PARAM_PRICE => 1200, ProductPrice::PARAM_DATE => '2026-01-01 00:00:00'],
            [ProductPrice::PARAM_PRICE => 1500, ProductPrice::PARAM_DATE => '2026-02-01 00:00:00'],
        ]);

        $this->assertTrue($result);
        $this->assertDatabaseHas(ProductPrice::TABLE_NAME, [
            ProductPrice::PARAM_PRODUCT_ID => $this->product->id,
            ProductPrice::PARAM_USER_ID => $this->user->id,
            ProductPrice::PARAM_PRICE => 1200,
        ]);
        $this->assertDatabaseHas(ProductPrice::TABLE_NAME, [
            ProductPrice::PARAM_PRODUCT_ID => $this->product->id,
            ProductPrice::PARAM_USER_ID => $this->user->id,
            ProductPrice::PARAM_PRICE => 1500,
        ]);
    }

    public function test_mass_save_with_empty_array_is_noop(): void
    {
        $result = $this->repository->massSave($this->product->id, []);

        $this->assertTrue($result);
        $this->assertDatabaseCount(ProductPrice::TABLE_NAME, 0);
    }

    public function test_mass_save_upserts_price_on_same_key(): void
    {
        $this->repository->massSave($this->product->id, [
            [ProductPrice::PARAM_PRICE => 1200, ProductPrice::PARAM_DATE => '2026-01-01 00:00:00'],
        ]);

        $this->repository->massSave($this->product->id, [
            [ProductPrice::PARAM_PRICE => 990, ProductPrice::PARAM_DATE => '2026-01-01 00:00:00'],
        ]);

        $this->assertSame(
            1,
            ProductPrice::query()->where(ProductPrice::PARAM_PRODUCT_ID, $this->product->id)->count(),
        );
        $this->assertDatabaseHas(ProductPrice::TABLE_NAME, [
            ProductPrice::PARAM_PRODUCT_ID => $this->product->id,
            ProductPrice::PARAM_USER_ID => $this->user->id,
            ProductPrice::PARAM_PRICE => 990,
        ]);
    }

    public function test_delete_removes_rows_of_current_user(): void
    {
        ProductPrice::factory()->count(2)->create([
            ProductPrice::PARAM_PRODUCT_ID => $this->product->id,
            ProductPrice::PARAM_USER_ID => $this->user->id,
        ]);

        $this->repository->delete($this->product->id);

        $this->assertDatabaseCount(ProductPrice::TABLE_NAME, 0);
    }

    public function test_delete_of_other_users_rows_is_silent_noop(): void
    {
        $others = ProductPrice::factory()->create([
            ProductPrice::PARAM_PRODUCT_ID => $this->product->id,
            ProductPrice::PARAM_USER_ID => User::factory()->create()->id,
        ]);

        $this->repository->delete($this->product->id);

        // assertModelExists неприменим: составной PK роняет getKey().
        $this->assertDatabaseHas(ProductPrice::TABLE_NAME, [
            ProductPrice::PARAM_PRODUCT_ID => $others->product_id,
            ProductPrice::PARAM_USER_ID => $others->user_id,
        ]);
    }
}
