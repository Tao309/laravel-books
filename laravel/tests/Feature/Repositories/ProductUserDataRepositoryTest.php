<?php

namespace Tests\Feature\Repositories;

use App\DTOs\ProductUserDataDTO;
use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\User;
use App\Repositories\ProductUserDataRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий ProductUserData скоупится по Auth::id(), get() — firstOrFail.
 * Поведение идентично BookUserDataRepository (единый интерфейс).
 */
class ProductUserDataRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductUserDataRepository $repository;

    private User $user;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(ProductUserDataRepository::class);
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();

        $this->actingAs($this->user);
    }

    private function dto(array $overrides = []): ProductUserDataDTO
    {
        return new ProductUserDataDTO(
            productId: $this->product->id,
            available: $overrides['available'] ?? false,
            isArchive: $overrides['is_archive'] ?? false,
            notAvailableDateFrom: $overrides['not_available_date_from'] ?? '2026-11-10 12:00:00',
            availableDateFrom: null,
            listenPriceValue: 1500,
            listenQtyValue: 3,
            releaseDate: null,
            comment: 'Проверить комплектацию',
        );
    }

    public function test_get_returns_row_for_current_user(): void
    {
        $userData = ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $this->product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
        ]);

        $found = $this->repository->get($this->product->id);

        // Model::is() неприменим: составной PK роняет getKey() —
        // сравниваем по ключевой паре.
        $this->assertSame($userData->user_id, $found->user_id);
        $this->assertSame($userData->product_id, $found->product_id);
    }

    public function test_get_throws_for_missing_row(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get($this->product->id);
    }

    public function test_get_is_scoped_to_current_user(): void
    {
        ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $this->product->id,
            ProductUserData::PARAM_USER_ID => User::factory()->create()->id,
        ]);

        $this->expectException(ModelNotFoundException::class);

        $this->repository->get($this->product->id);
    }

    public function test_create_persists_row_with_current_user(): void
    {
        $created = $this->repository->create($this->product->id, $this->dto());

        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $this->product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
            ProductUserData::PARAM_AVAILABLE => false,
            ProductUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
            ProductUserData::PARAM_LISTEN_QTY_VALUE => 3,
            ProductUserData::PARAM_COMMENT => 'Проверить комплектацию',
        ]);
        $this->assertSame($this->user->id, $created->user_id);
    }

    public function test_update_changes_fields_and_returns_affected_rows(): void
    {
        ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $this->product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
        ]);

        $affected = $this->repository->update($this->product->id, $this->dto([
            'is_archive' => true,
        ]));

        $this->assertSame(1, $affected);
        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $this->product->id,
            ProductUserData::PARAM_IS_ARCHIVE => true,
        ]);
    }

    public function test_update_missing_row_returns_zero(): void
    {
        $this->assertSame(0, $this->repository->update($this->product->id, $this->dto()));
    }

    public function test_delete_removes_row_of_current_user(): void
    {
        ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $this->product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
        ]);

        $this->repository->delete($this->product->id);

        $this->assertDatabaseCount(ProductUserData::TABLE_NAME, 0);
    }

    public function test_delete_of_other_users_row_is_silent_noop(): void
    {
        $others = ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $this->product->id,
            ProductUserData::PARAM_USER_ID => User::factory()->create()->id,
        ]);

        $this->repository->delete($this->product->id);

        // assertModelExists неприменим: составной PK роняет getKey().
        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $others->product_id,
            ProductUserData::PARAM_USER_ID => $others->user_id,
        ]);
    }
}
