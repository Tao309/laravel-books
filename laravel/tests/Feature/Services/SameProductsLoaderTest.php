<?php

namespace Tests\Feature\Services;

use App\Models\Book;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductUserData;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Models\User;
use App\Services\SameProductsLoader;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection as IlluminateCollection;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * SameProductsLoader: join по MIN(price) цен текущего пользователя,
 * группировка кандидатов по source_id в пределах source_type, guard
 * по source_type родителя (второй проход не затирает и не смешивает).
 * Явные PARAM_SHOP_ID для всех Product::factory(): секвенс shops
 * не сдвигается explicit-id фабриками (ozon=1, wb=2).
 */
class SameProductsLoaderTest extends TestCase
{
    use RefreshDatabase;

    private SameProductsLoader $loader;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Shop::factory()->ozon()->create();
        Shop::factory()->wb()->create();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->loader = $this->app->make(SameProductsLoader::class);
    }

    private function price(Product $product, int $price, ?User $user = null, string $date = '2026-01-01 10:00:00'): ProductPrice
    {
        return ProductPrice::factory()->create([
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
            ProductPrice::PARAM_USER_ID => ($user ?? $this->user)->id,
            ProductPrice::PARAM_PRICE => $price,
            ProductPrice::PARAM_DATE => $date,
        ]);
    }

    private function sourcedProduct(Book|SourceProduct $source, array $attributes = []): Product
    {
        return Product::factory()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_SOURCE_TYPE => $source::class,
            Product::PARAM_SOURCE_ID => $source->id,
            ...$attributes,
        ]);
    }

    public function test_load_sets_relation_for_book_sourced_products_excluding_self(): void
    {
        $book = Book::factory()->create();
        $first = $this->sourcedProduct($book);
        $second = $this->sourcedProduct($book);

        $this->price($first, 1500);
        $this->price($second, 1200);

        $this->loader->load(new Collection([$first, $second]));

        $this->assertSame(
            [$second->id],
            $first->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
        $this->assertSame(
            [$first->id],
            $second->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
    }

    public function test_load_sets_relation_for_source_product_sourced_products(): void
    {
        $sourceProduct = SourceProduct::factory()->create();
        $first = $this->sourcedProduct($sourceProduct);
        $second = $this->sourcedProduct($sourceProduct);

        $this->price($first, 1500);
        $this->price($second, 1200);

        $this->loader->load(new Collection([$first, $second]));

        $this->assertSame(
            [$second->id],
            $first->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
        $this->assertSame(
            [$first->id],
            $second->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
    }

    /**
     * Guard по source_type родителя: id у book и source_product — независимые
     * секвенсы, поэтому source_product с тем же числовым id, что у книги,
     * не должен попасть в same_products книжного товара.
     */
    public function test_load_does_not_match_candidates_across_source_types(): void
    {
        $book = Book::factory()->create();

        $twinSourceProduct = SourceProduct::factory()->create([
            SourceProduct::PARAM_ID => $book->id,
        ]);

        $parent = $this->sourcedProduct($book);
        $bookCandidate = $this->sourcedProduct($book);
        $sourceProductCandidate = $this->sourcedProduct($twinSourceProduct);

        $this->price($parent, 100);
        $this->price($bookCandidate, 200);
        $this->price($sourceProductCandidate, 50);

        $this->loader->load(new Collection([$parent]));

        // более дешёвый twin-source candidate не пролез через коллизию id
        $this->assertSame(
            [$bookCandidate->id],
            $parent->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
    }

    /**
     * Два прохода (по Book и по SourceProduct): guard пропускает родителя
     * чужого source_type, поэтому setRelation второго прохода не затирает
     * результат первого.
     */
    public function test_load_keeps_book_relations_after_source_product_pass(): void
    {
        $book = Book::factory()->create();
        $sourceProduct = SourceProduct::factory()->create();

        $bookParent = $this->sourcedProduct($book);
        $bookSame = $this->sourcedProduct($book);
        $sourceProductParent = $this->sourcedProduct($sourceProduct);
        $sourceProductSame = $this->sourcedProduct($sourceProduct);

        foreach ([$bookParent, $bookSame, $sourceProductParent, $sourceProductSame] as $product) {
            $this->price($product, 100);
        }

        $this->loader->load(new Collection([$bookParent, $sourceProductParent]));

        $this->assertSame(
            [$bookSame->id],
            $bookParent->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
        $this->assertSame(
            [$sourceProductSame->id],
            $sourceProductParent->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
    }

    public function test_load_accepts_single_product(): void
    {
        $book = Book::factory()->create();
        $parent = $this->sourcedProduct($book);
        $same = $this->sourcedProduct($book);

        $this->price($parent, 1500);
        $this->price($same, 1200);

        $this->loader->load($parent);

        $this->assertSame(
            [$same->id],
            $parent->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
    }

    /**
     * Поддержка Illuminate\Support\Collection: нормализуется в Eloquent
     * Collection, где()/pluck() работают одинаково на атрибутах моделей.
     */
    public function test_load_accepts_illuminate_support_collection(): void
    {
        $book = Book::factory()->create();
        $first = $this->sourcedProduct($book);
        $second = $this->sourcedProduct($book);

        $this->price($first, 1500);
        $this->price($second, 1200);

        $this->loader->load(new IlluminateCollection([$first, $second]));

        $this->assertSame(
            [$second->id],
            $first->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
        $this->assertSame(
            [$first->id],
            $second->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
    }

    public function test_load_with_empty_collection_is_noop(): void
    {
        $this->loader->load(new Collection);
        $this->loader->load(new IlluminateCollection);

        $this->assertDatabaseCount(Product::TABLE_NAME, 0);
    }

    /**
     * Документация дефекта: subQuery скоупится где user_id = Auth::id(),
     * без пользователя рендерится как is null → join пуст, relation
     * выставляется пустым (а не остаётся незагруженным).
     */
    public function test_load_without_current_user_sets_empty_relation(): void
    {
        Auth::logout();

        $book = Book::factory()->create();
        $parent = $this->sourcedProduct($book);
        $candidate = $this->sourcedProduct($book);

        $this->price($parent, 100);
        $this->price($candidate, 200);

        $this->loader->load(new Collection([$parent]));

        $this->assertTrue($parent->relationLoaded(Product::PARAM_SAME_PRODUCTS));
        $this->assertSame([], $parent->getSameProducts()->all());
    }

    public function test_same_products_carry_joined_min_price_attribute(): void
    {
        $book = Book::factory()->create();
        $parent = $this->sourcedProduct($book);
        $candidate = $this->sourcedProduct($book);

        $this->price($parent, 1500);
        $this->price($candidate, 1000, null, '2026-01-01 10:00:00');
        $this->price($candidate, 800, null, '2026-02-01 10:00:00');

        $this->loader->load(new Collection([$parent]));

        $this->assertSame(800, $parent->getSameProducts()->first()->min_price);
    }

    public function test_min_price_scope_ignores_other_users_prices(): void
    {
        $otherUser = User::factory()->create();

        $book = Book::factory()->create();
        $parent = $this->sourcedProduct($book);
        $candidate = $this->sourcedProduct($book);

        $this->price($parent, 1500);
        $this->price($candidate, 800);
        $this->price($candidate, 100, $otherUser);

        $this->loader->load(new Collection([$parent]));

        $this->assertSame(800, $parent->getSameProducts()->first()->min_price);
    }

    /**
     * Товар того же магазина идёт первым, дальше — по возрастанию min_price.
     */
    public function test_same_products_sorted_same_shop_first_then_by_min_price(): void
    {
        $book = Book::factory()->create();
        $parent = $this->sourcedProduct($book);

        $sameShop = $this->sourcedProduct($book);
        $foreignCheap = $this->sourcedProduct($book, [Product::PARAM_SHOP_ID => 2]);
        $foreignMid = $this->sourcedProduct($book, [Product::PARAM_SHOP_ID => 2]);

        $this->price($parent, 100);
        $this->price($sameShop, 900);
        $this->price($foreignCheap, 100);
        $this->price($foreignMid, 500);

        $this->loader->load(new Collection([$parent]));

        $this->assertSame(
            [$sameShop->id, $foreignCheap->id, $foreignMid->id],
            $parent->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
    }

    public function test_same_products_exclude_products_without_user_prices(): void
    {
        $book = Book::factory()->create();
        $parent = $this->sourcedProduct($book);
        $withPrice = $this->sourcedProduct($book);
        $withoutPrice = $this->sourcedProduct($book);

        $this->price($parent, 100);
        $this->price($withPrice, 200);

        $this->loader->load(new Collection([$parent]));

        $this->assertSame(
            [$withPrice->id],
            $parent->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
    }

    public function test_same_products_have_shop_and_product_user_data_eager_loaded(): void
    {
        $book = Book::factory()->create();
        $parent = $this->sourcedProduct($book);
        $candidate = $this->sourcedProduct($book);

        $this->price($parent, 100);
        $this->price($candidate, 200);

        ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $candidate->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
            ProductUserData::PARAM_AVAILABLE => true,
        ]);

        $this->loader->load(new Collection([$parent]));

        $same = $parent->getSameProducts()->first();

        $this->assertTrue($same->relationLoaded(Product::PARAM_SHOP));
        $this->assertSame(1, $same->shop->id);
        $this->assertTrue($same->relationLoaded('productUserData'));
        $this->assertTrue($same->productUserData->available);
    }
}
