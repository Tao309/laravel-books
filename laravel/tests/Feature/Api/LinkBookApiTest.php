<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\Product;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

class LinkBookApiTest extends TestCase
{
    use ActsAsApiClient;

    public function test_link_book_sets_product_source(): void
    {
        $product = $this->makeProduct();
        $book = Book::factory()->create();

        $response = $this->apiPost("/api/products/link_book/{$product->id}/{$book->id}");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.book.id', $book->id)
            ->assertJsonPath('data.source_product', null);

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_SOURCE_TYPE => Book::class,
            Product::PARAM_SOURCE_ID => $book->id,
        ]);
    }

    public function test_link_book_rejects_product_that_already_has_a_source(): void
    {
        $product = Product::factory()->forBook()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_USER_CREATED_ID => $this->user->id,
        ]);
        $book = Book::factory()->create();

        $this->apiPost("/api/products/link_book/{$product->id}/{$book->id}")
            ->assertBadRequest()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Product already has Book or SourceProduct');
    }

    public function test_link_book_reports_missing_book(): void
    {
        $product = $this->makeProduct();

        $this->apiPost("/api/products/link_book/{$product->id}/999999")
            ->assertBadRequest()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Not found Book by id = 999999');
    }

    private function makeProduct(): Product
    {
        return Product::factory()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_USER_CREATED_ID => $this->user->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ]);
    }
}
