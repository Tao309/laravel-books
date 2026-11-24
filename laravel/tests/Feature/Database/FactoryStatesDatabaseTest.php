<?php

namespace Tests\Feature\Database;

use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookUserData;
use App\Models\City;
use App\Models\PaperType;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\ProductUserData;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactoryStatesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_simple_factories_create_models_with_unique_key_attributes(): void
    {
        $author = Author::factory()->create();
        $bindingType = BindingType::factory()->create();
        $city = City::factory()->create();
        $paperType = PaperType::factory()->create();
        $brand = PublishingBrand::factory()->create();
        $house = PublishingHouse::factory()->create();
        $sourceType = SourceProductType::factory()->create();
        $shop = Shop::factory()->create();

        $this->assertNotEmpty($author->first_name);
        $this->assertNotEmpty($author->last_name);
        $this->assertNotEmpty($bindingType->label);
        $this->assertSame(mb_strtoupper(mb_substr($city->title, 0, 3)), $city->code);
        $this->assertNotEmpty($paperType->label);
        $this->assertNotEmpty($brand->name);
        $this->assertNotEmpty($house->name);
        $this->assertNotEmpty($sourceType->code);
        $this->assertNotSame($sourceType->code, $sourceType->name);
        $this->assertNotEmpty($shop->type);
        $this->assertNotEmpty($shop->domain);
    }

    public function test_shop_factory_states_use_fixed_identifiers(): void
    {
        $ozon = Shop::factory()->ozon()->create();
        $wb = Shop::factory()->wb()->create();
        $chitaiGorod = Shop::factory()->chitaiGorod()->create();

        $this->assertSame(1, $ozon->id);
        $this->assertSame('ozon', $ozon->type);
        $this->assertSame('ozon.ru', $ozon->domain);
        $this->assertSame(2, $wb->id);
        $this->assertSame('wildberries', $wb->type);
        $this->assertSame(3, $chitaiGorod->id);
        $this->assertSame('chitai-gorod', $chitaiGorod->type);
    }

    public function test_reference_factory_states_are_unique_and_stable(): void
    {
        $states = [
            ['hardcover', BindingType::factory()->hardcover(), BindingType::PARAM_LABEL, BindingType::PARAM_ID, 1],
            ['softcover', BindingType::factory()->softcover(), BindingType::PARAM_LABEL, BindingType::PARAM_ID, 2],
            ['krasnodar', City::factory()->krasnodar(), City::PARAM_TITLE, City::PARAM_ID, 1],
            ['moscow', City::factory()->moscow(), City::PARAM_TITLE, City::PARAM_ID, 2],
            ['spb', City::factory()->spb(), City::PARAM_TITLE, City::PARAM_ID, 3],
            ['typographic', PaperType::factory()->typographic(), PaperType::PARAM_LABEL, PaperType::PARAM_ID, 1],
            ['offset', PaperType::factory()->offset(), PaperType::PARAM_LABEL, PaperType::PARAM_ID, 2],
            ['newsprint', PaperType::factory()->newsprint(), PaperType::PARAM_LABEL, PaperType::PARAM_ID, 3],
            ['shoes', SourceProductType::factory()->shoes(), SourceProductType::PARAM_CODE, SourceProductType::PARAM_ID, null],
            ['shirts', SourceProductType::factory()->shirts(), SourceProductType::PARAM_CODE, SourceProductType::PARAM_ID, null],
            ['beverages', SourceProductType::factory()->beverages(), SourceProductType::PARAM_CODE, SourceProductType::PARAM_ID, null],
            ['medicines', SourceProductType::factory()->medicines(), SourceProductType::PARAM_CODE, SourceProductType::PARAM_ID, null],
            ['food', SourceProductType::factory()->food(), SourceProductType::PARAM_CODE, SourceProductType::PARAM_ID, null],
        ];

        $seen = [];

        foreach ($states as [$state, $factory, $nameParam, $idParam, $expectedId]) {
            $model = $factory->make();

            if ($expectedId !== null) {
                $this->assertSame($expectedId, $model->{$idParam});
            }
            $this->assertNotEmpty($model->{$nameParam});

            $key = $model::class . ':' . $model->{$nameParam};
            $this->assertArrayNotHasKey($key, $seen, "Duplicate label for {$state}");
            $seen[$key] = true;
        }
    }

    public function test_book_factory_creates_related_models_and_user_data(): void
    {
        $creator = User::factory()->create();
        $book = Book::factory()->create([
            Book::PARAM_USER_CREATED_ID => $creator->id,
        ]);

        $this->assertModelExists($book);
        $this->assertInstanceOf(BindingType::class, $book->bindingType);
        $this->assertInstanceOf(PaperType::class, $book->paperType);
        $this->assertInstanceOf(PublishingHouse::class, $book->publishingHouse);
        $this->assertInstanceOf(PublishingBrand::class, $book->publishingBrand);
        $this->assertInstanceOf(User::class, $book->userCreated);

        $this->assertDatabaseHas(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $creator->id,
        ]);

        $withAuthors = Book::factory()->hasAttached(Author::factory()->count(2), [], 'authors')->create([
            Book::PARAM_USER_CREATED_ID => $creator->id,
        ]);

        $this->assertCount(2, $withAuthors->authors);
        foreach ($withAuthors->authors as $author) {
            $this->assertInstanceOf(Author::class, $author);
        }
    }

    public function test_source_product_factory_creates_user_data_for_creator(): void
    {
        $creator = User::factory()->create();
        $sourceProduct = SourceProduct::factory()->create([
            SourceProduct::PARAM_USER_CREATED_ID => $creator->id,
        ]);

        $this->assertInstanceOf(SourceProductType::class, $sourceProduct->sourceProductType);
        $this->assertInstanceOf(User::class, $sourceProduct->userCreated);
        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $creator->id,
        ]);
    }

    public function test_user_data_factories_create_rows_with_composite_keys(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([Book::PARAM_USER_CREATED_ID => $user->id]);
        $sourceProduct = SourceProduct::factory()->create([
            SourceProduct::PARAM_USER_CREATED_ID => $user->id,
        ]);
        $product = Product::factory()->create([
            Product::PARAM_USER_CREATED_ID => $user->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ]);

        BookUserData::where([
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $user->id,
        ])->delete();
        $bookData = BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $user->id,
        ]);
        SourceProductUserData::where([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $user->id,
        ])->delete();
        $sourceData = SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $user->id,
        ]);
        $productData = ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $user->id,
        ]);
        $price = ProductPrice::factory()->create([
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
            ProductPrice::PARAM_USER_ID => $user->id,
        ]);
        $stock = ProductStock::factory()->create([
            ProductStock::PARAM_PRODUCT_ID => $product->id,
            ProductStock::PARAM_USER_ID => $user->id,
        ]);

        $this->assertSame($book->id, $bookData->book_id);
        $this->assertSame($sourceProduct->id, $sourceData->source_product_id);
        $this->assertSame($product->id, $productData->product_id);
        $this->assertDatabaseHas(ProductPrice::TABLE_NAME, [
            ProductPrice::PARAM_PRODUCT_ID => $price->product_id,
            ProductPrice::PARAM_USER_ID => $price->user_id,
            ProductPrice::PARAM_DATE => $price->date->toDateTimeString(),
        ]);
        $this->assertDatabaseHas(ProductStock::TABLE_NAME, [
            ProductStock::PARAM_PRODUCT_ID => $stock->product_id,
            ProductStock::PARAM_USER_ID => $stock->user_id,
            ProductStock::PARAM_DATE => $stock->date->toDateTimeString(),
        ]);
    }

    public function test_product_factory_states_and_random_sources(): void
    {
        $forBook = Product::factory()->forBook()->create();
        $forSource = Product::factory()->forSourceProduct()->create();

        $this->assertSame(Book::class, $forBook->source_type);
        $this->assertInstanceOf(Book::class, $forBook->source);
        $this->assertSame(SourceProduct::class, $forSource->source_type);
        $this->assertInstanceOf(SourceProduct::class, $forSource->source);

        $withBook = Product::factory()->forBook()->create();
        $withSource = Product::factory()->forSourceProduct()->create();
        $withoutSource = Product::factory()->create([
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ]);

        $this->assertInstanceOf(Book::class, $withBook->source);
        $this->assertInstanceOf(SourceProduct::class, $withSource->source);
        $this->assertNull($withoutSource->source_type);
        $this->assertNull($withoutSource->source_id);
    }

    public function test_user_data_factories_create_their_own_related_models(): void
    {
        $bookData = BookUserData::factory()->create([
            BookUserData::PARAM_COMMENT => 'factory relation check',
        ]);
        $sourceData = SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_COMMENT => 'factory relation check',
        ]);
        $productData = ProductUserData::factory()->create([
            ProductUserData::PARAM_COMMENT => 'factory relation check',
        ]);
        $price = ProductPrice::factory()->create();
        $stock = ProductStock::factory()->create();

        $this->assertInstanceOf(Book::class, $bookData->book);
        $this->assertInstanceOf(SourceProduct::class, $sourceData->book);
        $this->assertInstanceOf(Product::class, $productData->product);

        $this->assertDatabaseHas(User::TABLE_NAME, [User::PARAM_ID => $bookData->user_id]);
        $this->assertDatabaseHas(User::TABLE_NAME, [User::PARAM_ID => $sourceData->user_id]);
        $this->assertDatabaseHas(User::TABLE_NAME, [User::PARAM_ID => $productData->user_id]);
        $this->assertDatabaseHas(Product::TABLE_NAME, [Product::PARAM_ID => $price->product_id]);
        $this->assertDatabaseHas(User::TABLE_NAME, [User::PARAM_ID => $price->user_id]);
        $this->assertDatabaseHas(Product::TABLE_NAME, [Product::PARAM_ID => $stock->product_id]);
        $this->assertDatabaseHas(User::TABLE_NAME, [User::PARAM_ID => $stock->user_id]);
    }

    public function test_book_with_authors_state_characterizes_current_broken_signature(): void
    {
        // Characterization: BookFactory::withAuthors() передаёт 'authors' как pivot-атрибуты,
        // поэтому фабричный state сейчас падает с TypeError. Перевернуть тест после исправления app.
        $this->expectException(\TypeError::class);

        Book::factory()->withAuthors(1)->create();
    }

    public function test_user_factory_unverified_state(): void
    {
        $verified = User::factory()->create();
        $unverified = User::factory()->unverified()->create();

        $this->assertNotNull($verified->email_verified_at);
        $this->assertNull($unverified->email_verified_at);
    }
}
