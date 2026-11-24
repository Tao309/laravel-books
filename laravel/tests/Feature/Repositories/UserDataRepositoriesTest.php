<?php

namespace Tests\Feature\Repositories;

use App\DTOs\BookUserDataDTO;
use App\DTOs\ProductUserDataDTO;
use App\DTOs\SourceProductUserDataDTO;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\BookUserData;
use App\Models\PaperType;
use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use App\Models\User;
use App\Repositories\BookUserDataRepository;
use App\Repositories\ProductUserDataRepository;
use App\Repositories\SourceProductUserDataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDataRepositoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_user_data_repository_is_scoped_to_authenticated_user(): void
    {
        $creator = User::factory()->create();
        $book = $this->makeBook($creator);
        $user = $this->actingUser();

        $repository = $this->app->make(BookUserDataRepository::class);
        $created = $repository->create($book->id, BookUserDataDTO::fromArray([
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_COMMENT => 'First comment',
        ]));

        $this->assertSame($user->id, $created->user_id);
        $this->assertSame($book->id, $created->book_id);
        $this->assertSame($created->book_id, $repository->get($book->id)->book_id);

        $updatedRows = $repository->update($book->id, BookUserDataDTO::fromArray([
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_COMMENT => 'Updated comment',
        ]));

        $this->assertSame(1, $updatedRows);
        $this->assertDatabaseHas(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $user->id,
            BookUserData::PARAM_COMMENT => 'Updated comment',
        ]);

        $repository->delete($book->id);

        $this->assertDatabaseMissing(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $user->id,
        ]);
        $this->assertDatabaseHas(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $creator->id,
        ]);
    }

    public function test_product_user_data_repository_is_scoped_to_authenticated_user(): void
    {
        $user = $this->actingUser();
        $product = Product::factory()->create([
            Product::PARAM_SHOP_ID => $this->makeShop()->id,
            Product::PARAM_CITY_ID => null,
            Product::PARAM_USER_CREATED_ID => $user->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
            Product::PARAM_SHOP_PRODUCT_ID => 'pud-repo-' . uniqid(),
        ]);

        $repository = $this->app->make(ProductUserDataRepository::class);
        $created = $repository->create($product->id, ProductUserDataDTO::fromArray([
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_AVAILABLE => true,
            ProductUserData::PARAM_IS_ARCHIVE => false,
        ]));

        $this->assertSame($user->id, $created->user_id);
        $this->assertSame($created->product_id, $repository->get($product->id)->product_id);

        $updatedRows = $repository->update($product->id, ProductUserDataDTO::fromArray([
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_IS_ARCHIVE => true,
        ]));

        $this->assertSame(1, $updatedRows);
        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $user->id,
            ProductUserData::PARAM_IS_ARCHIVE => true,
        ]);

        $repository->delete($product->id);

        $this->assertDatabaseMissing(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $user->id,
        ]);
    }

    public function test_source_product_user_data_repository_is_scoped_to_authenticated_user(): void
    {
        $creator = User::factory()->create();
        $sourceProduct = $this->makeSourceProduct($creator);
        $user = $this->actingUser();

        $repository = $this->app->make(SourceProductUserDataRepository::class);
        $created = $repository->create($sourceProduct->id, SourceProductUserDataDTO::fromArray([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_COMMENT => 'Source comment',
        ]));

        $this->assertSame($user->id, $created->user_id);
        $this->assertSame($created->source_product_id, $repository->get($sourceProduct->id)->source_product_id);

        $updatedRows = $repository->update($sourceProduct->id, SourceProductUserDataDTO::fromArray([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_COMMENT => 'Updated source comment',
        ]));

        $this->assertSame(1, $updatedRows);
        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $user->id,
            SourceProductUserData::PARAM_COMMENT => 'Updated source comment',
        ]);

        $repository->delete($sourceProduct->id);

        $this->assertDatabaseMissing(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $user->id,
        ]);
        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $creator->id,
        ]);
    }

    private function actingUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    private function makeShop(): Shop
    {
        $suffix = uniqid();

        return Shop::factory()->create([
            Shop::PARAM_TYPE => 'repo-' . $suffix,
            Shop::PARAM_DOMAIN => 'repo-' . $suffix . '.test',
        ]);
    }

    private function makeBook(User $creator): Book
    {
        $suffix = uniqid();

        return Book::factory()->create([
            Book::PARAM_BINDING_TYPE_ID => BindingType::factory()->create([BindingType::PARAM_LABEL => 'repo-' . $suffix])->id,
            Book::PARAM_PAPER_TYPE_ID => PaperType::factory()->create([PaperType::PARAM_LABEL => 'repo-' . $suffix])->id,
            Book::PARAM_PUBLISHING_HOUSE_ID => PublishingHouse::factory()->create([PublishingHouse::PARAM_NAME => 'repo-' . $suffix])->id,
            Book::PARAM_PUBLISHING_BRAND_ID => PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => 'repo-' . $suffix])->id,
            Book::PARAM_BOOK_SERIES_ID => BookSeries::factory()->create([BookSeries::PARAM_NAME => 'repo-' . $suffix])->id,
            Book::PARAM_USER_CREATED_ID => $creator->id,
        ]);
    }

    private function makeSourceProduct(User $creator): SourceProduct
    {
        $suffix = uniqid();

        return SourceProduct::factory()->create([
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => SourceProductType::factory()->create([
                SourceProductType::PARAM_CODE => 'repo-' . $suffix,
                SourceProductType::PARAM_NAME => 'Repo type ' . $suffix,
            ])->id,
            SourceProduct::PARAM_USER_CREATED_ID => $creator->id,
        ]);
    }
}
