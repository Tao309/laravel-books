<?php

namespace Tests\Feature\Repositories;

use App\DTOs\SourceProductUserDataDTO;
use App\Models\SourceProduct;
use App\Models\SourceProductUserData;
use App\Models\User;
use App\Repositories\SourceProductUserDataRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий SourceProductUserData скоупится по Auth::id(),
 * get() — firstOrFail. Единый интерфейс с Book/ProductUserData.
 */
class SourceProductUserDataRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private SourceProductUserDataRepository $repository;

    private User $user;

    private SourceProduct $sourceProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(SourceProductUserDataRepository::class);
        $this->user = User::factory()->create();

        // ВАЖНО: до actingAs. SourceProductFactory::configure() в
        // afterCreating создаёт user_data для Auth::id() (или владельца) —
        // при создании после actingAs строка была бы привязана к $this->user.
        $this->sourceProduct = SourceProduct::factory()->create();

        $this->actingAs($this->user);
    }

    private function dto(array $overrides = []): SourceProductUserDataDTO
    {
        return new SourceProductUserDataDTO(
            sourceProductId: $this->sourceProduct->id,
            listenPriceValue: $overrides['listen_price_value'] ?? 1500,
            comment: $overrides['comment'] ?? 'Нужен размер 43',
        );
    }

    public function test_get_returns_row_for_current_user(): void
    {
        $userData = SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $this->sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
        ]);

        $found = $this->repository->get($this->sourceProduct->id);

        // Model::is() неприменим: составной PK роняет getKey() —
        // сравниваем по ключевой паре.
        $this->assertSame($userData->user_id, $found->user_id);
        $this->assertSame($userData->source_product_id, $found->source_product_id);
    }

    public function test_get_throws_for_missing_row(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get($this->sourceProduct->id);
    }

    public function test_get_is_scoped_to_current_user(): void
    {
        SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $this->sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => User::factory()->create()->id,
        ]);

        $this->expectException(ModelNotFoundException::class);

        $this->repository->get($this->sourceProduct->id);
    }

    public function test_create_persists_row_with_current_user(): void
    {
        $created = $this->repository->create($this->sourceProduct->id, $this->dto());

        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $this->sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
            SourceProductUserData::PARAM_COMMENT => 'Нужен размер 43',
        ]);
        $this->assertSame($this->user->id, $created->user_id);
    }

    public function test_update_changes_fields_and_returns_affected_rows(): void
    {
        SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $this->sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
        ]);

        $affected = $this->repository->update($this->sourceProduct->id, $this->dto([
            'comment' => 'Обновлённый комментарий',
        ]));

        $this->assertSame(1, $affected);
        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $this->sourceProduct->id,
            SourceProductUserData::PARAM_COMMENT => 'Обновлённый комментарий',
        ]);
    }

    public function test_update_missing_row_returns_zero(): void
    {
        $this->assertSame(0, $this->repository->update($this->sourceProduct->id, $this->dto()));
    }

    public function test_delete_removes_row_of_current_user(): void
    {
        SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $this->sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
        ]);

        $this->repository->delete($this->sourceProduct->id);

        // строка владельца (создана фабрикой source product) остаётся —
        // delete скоупится только по текущему пользователю
        $this->assertDatabaseMissing(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $this->sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
        ]);
    }

    public function test_delete_of_other_users_row_is_silent_noop(): void
    {
        $others = SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $this->sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => User::factory()->create()->id,
        ]);

        $this->repository->delete($this->sourceProduct->id);

        // assertModelExists неприменим: составной PK роняет getKey().
        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $others->source_product_id,
            SourceProductUserData::PARAM_USER_ID => $others->user_id,
        ]);
    }
}
