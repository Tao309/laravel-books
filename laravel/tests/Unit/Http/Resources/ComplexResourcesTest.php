<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\AbstractResource;
use App\Http\Resources\AbstractResourceCollection;
use App\Http\Resources\Authors\AuthorResource;
use App\Http\Resources\Authors\AuthorsResourceCollection;
use App\Http\Resources\BindingTypes\BindingTypeResource;
use App\Http\Resources\BindingTypes\BindingTypesResourceCollection;
use App\Http\Resources\Books\BookResource;
use App\Http\Resources\Books\BooksResourceCollection;
use App\Http\Resources\BookSeries\BookSeriesResource;
use App\Http\Resources\BookSeries\BookSeriesResourceCollection;
use App\Http\Resources\Cities\CitiesResourceCollection;
use App\Http\Resources\Cities\CityResource;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\PaperTypes\PaperTypeResource;
use App\Http\Resources\PaperTypes\PaperTypesResourceCollection;
use App\Http\Resources\Products\MassSaveResourceCollection;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\ProductsResourceCollection;
use App\Http\Resources\Products\SameProductResource;
use App\Http\Resources\PublishingBrand\PublishingBrandResource;
use App\Http\Resources\PublishingBrand\PublishingBrandsResourceCollection;
use App\Http\Resources\PublishingHouses\PublishingHouseResource;
use App\Http\Resources\PublishingHouses\PublishingHousesResourceCollection;
use App\Http\Resources\Shops\ShopResource;
use App\Http\Resources\Shops\ShopsResourceCollection;
use App\Http\Resources\SourceProducts\SourceProductResource;
use App\Http\Resources\SourceProducts\SourceProductsResourceCollection;
use App\Http\Resources\SourceProductTypes\SourceProductTypeResource;
use App\Http\Resources\SourceProductTypes\SourceProductTypesResourceCollection;
use App\Http\Resources\Users\UserResource;
use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\City;
use App\Models\PaperType;
use App\Models\Product;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use Tests\TestCase;

class ComplexResourcesTest extends TestCase
{
    public static function collectionProvider(): array
    {
        return [
            'authors' => [AuthorsResourceCollection::class, AuthorResource::class, Author::class, []],
            'binding types' => [BindingTypesResourceCollection::class, BindingTypeResource::class, BindingType::class, [
                BindingType::PARAM_LABEL => 'Collection binding',
            ]],
            'book series' => [BookSeriesResourceCollection::class, BookSeriesResource::class, BookSeries::class, [
                BookSeries::PARAM_NAME => 'Collection series',
            ]],
            'books' => [BooksResourceCollection::class, BookResource::class, Book::class, self::bookAttributes()],
            'cities' => [CitiesResourceCollection::class, CityResource::class, City::class, [
                City::PARAM_CODE => 'COL',
                City::PARAM_TITLE => 'Collection city',
            ]],
            'paper types' => [PaperTypesResourceCollection::class, PaperTypeResource::class, PaperType::class, [
                PaperType::PARAM_LABEL => 'Collection paper',
            ]],
            'products' => [ProductsResourceCollection::class, ProductResource::class, Product::class, self::productAttributes()],
            'publishing brands' => [PublishingBrandsResourceCollection::class, PublishingBrandResource::class, PublishingBrand::class, [
                PublishingBrand::PARAM_NAME => 'Collection brand',
            ]],
            'publishing houses' => [PublishingHousesResourceCollection::class, PublishingHouseResource::class, PublishingHouse::class, [
                PublishingHouse::PARAM_NAME => 'Collection house',
            ]],
            'shops' => [ShopsResourceCollection::class, ShopResource::class, Shop::class, [
                Shop::PARAM_TYPE => 'collection-shop',
                Shop::PARAM_DOMAIN => 'collection.test',
            ]],
            'source product types' => [SourceProductTypesResourceCollection::class, SourceProductTypeResource::class, SourceProductType::class, [
                SourceProductType::PARAM_CODE => 'collection-type',
                SourceProductType::PARAM_NAME => 'Collection type',
            ]],
            'source products' => [SourceProductsResourceCollection::class, SourceProductResource::class, SourceProduct::class, self::sourceProductAttributes()],
            'mass save' => [MassSaveResourceCollection::class, ProductResource::class, Product::class, self::productAttributes()],
        ];
    }

    #[DataProvider('collectionProvider')]
    public function test_resource_collections_expose_items_and_count(
        string $collectionClass,
        string $collects,
        string $modelClass,
        array $attributes,
    ): void {
        $models = collect([
            $modelClass::factory()->make($attributes),
            $modelClass::factory()->make($attributes),
        ]);

        $collection = new $collectionClass($models);

        $this->assertSame($collects, $collection->collects);
        $data = $collection->toArray(new Request);
        $this->assertSame(['items', 'meta'], array_keys($data));
        $this->assertSame(2, $data['meta']['count']);
        $this->assertCount(2, $data['items']);
    }

    public function test_mass_save_collection_spreads_additional_metadata(): void
    {
        $collection = (new MassSaveResourceCollection(collect([$this->makeProduct()])))
            ->additional([
                'products_count' => 1,
                'errors_count' => 0,
                'saved_count' => 1,
                'error_messages' => [],
            ]);

        $data = $collection->toArray(new Request);

        $this->assertSame(1, $data['products_count']);
        $this->assertSame(0, $data['errors_count']);
        $this->assertSame(1, $data['saved_count']);
        $this->assertSame([], $data['error_messages']);
    }

    public function test_base_collection_keeps_raw_items_and_count(): void
    {
        $collection = new class(collect([1, 2, 3])) extends AbstractResourceCollection {};
        $data = $collection->toArray(new Request);

        $this->assertCount(3, $data['items']);
        $this->assertSame(3, $data['meta']['count']);
    }

    public function test_abstract_resource_contract(): void
    {
        $reflection = new ReflectionClass(AbstractResource::class);

        $this->assertTrue($reflection->isAbstract());
        $this->assertTrue($reflection->hasMethod('getArrayData'));
        $this->assertTrue($reflection->getMethod('getArrayData')->isAbstract());
    }

    public function test_user_resource_characterizes_current_username_mapping(): void
    {
        $user = User::factory()->make([User::PARAM_NAME => 'reader']);

        $data = (new UserResource($user))->resolve(new Request);

        $this->assertSame([
            'id', 'name', 'username', 'email', 'is_active', Model::UPDATED_AT, Model::CREATED_AT,
        ], array_keys($data));
        $this->assertSame('reader', $data['username']);
        $this->assertTrue($data['is_active']);
    }

    public function test_book_resource_exposes_scalars_and_loaded_relations_only(): void
    {
        $book = $this->makeBook();
        $data = (new BookResource($book))->resolve(new Request);

        $this->assertSame([
            'id', 'title', 'original_title', 'isbn', 'pages', 'circulation', 'size', 'publish_year',
            'livelib_id', 'goodreads_id', 'fantlab_id', 'livelib_rating', 'goodreads_rating',
            'on_sale_count', Model::UPDATED_AT, Model::CREATED_AT,
        ], array_keys($data));

        $book->setRelation('authors', collect([Author::factory()->make()]));
        $book->setRelation('bindingType', BindingType::factory()->make([BindingType::PARAM_LABEL => 'Book binding']));

        $data = (new BookResource($book))->resolve(new Request);

        $this->assertArrayHasKey('authors', $data);
        $this->assertArrayHasKey('binding_type', $data);
        $this->assertArrayNotHasKey('paper_type', $data);
    }

    public function test_product_resource_uses_min_price_when_present(): void
    {
        $product = $this->makeProduct();

        $data = (new ProductResource($product))->resolve(new Request);

        $this->assertSame([
            'id', 'shop_product_id', 'shop_product_code', 'title',
            Model::UPDATED_AT, Model::CREATED_AT,
        ], array_keys($data));

        $product->setAttribute(Product::PARAM_MIN_PRICE, 123);

        $data = (new ProductResource($product))->resolve(new Request);
        $this->assertSame(123, $data['min_price']);
        $this->assertArrayNotHasKey('last_qty', $data);
    }

    public function test_product_resource_exposes_last_stock_when_stocks_are_loaded(): void
    {
        $product = $this->makeProduct();
        $product->setRelation('stocks', collect());

        $data = (new ProductResource($product))->resolve(new Request);

        $this->assertArrayHasKey('last_qty', $data);
        $this->assertArrayHasKey('last_stock', $data);
        $this->assertArrayHasKey('stocks', $data);
        $this->assertNull($data['last_qty']);
        $this->assertNull($data['last_stock']);
    }

    public function test_product_resource_uses_source_type_for_book_or_source_product_key(): void
    {
        $product = $this->makeProduct();

        $product->setRelation('source', $this->makeBook());
        $withBook = (new ProductResource($product))->resolve(new Request);
        $this->assertNotNull($withBook['book']);
        $this->assertNull($withBook['source_product']);

        $product->setRelation('source', $this->makeSourceProduct());
        $withSourceProduct = (new ProductResource($product))->resolve(new Request);
        $this->assertNull($withSourceProduct['book']);
        $this->assertNotNull($withSourceProduct['source_product']);
    }

    public function test_same_product_resource_always_exposes_min_price(): void
    {
        $product = $this->makeProduct([Product::PARAM_MIN_PRICE => 321]);

        $data = (new SameProductResource($product))->resolve(new Request);

        $this->assertSame([
            'id', 'shop_product_id', 'shop_product_code', 'title', 'min_price',
        ], array_keys($data));
        $this->assertSame(321, $data['min_price']);

        $product->setRelation('shop', Shop::factory()->make([
            Shop::PARAM_TYPE => 'same-shop',
            Shop::PARAM_DOMAIN => 'same.test',
        ]));
        $data = (new SameProductResource($product))->resolve(new Request);
        $this->assertArrayHasKey('shop', $data);
        $this->assertArrayNotHasKey('product_user_data', $data);
    }

    public function test_source_product_resource_uses_loaded_relations(): void
    {
        $sourceProduct = $this->makeSourceProduct();

        $data = (new SourceProductResource($sourceProduct))->resolve(new Request);
        $this->assertSame([
            'id', 'title', 'on_sale_count', Model::UPDATED_AT, Model::CREATED_AT,
        ], array_keys($data));

        $sourceProduct->setRelation('sourceProductType', SourceProductType::factory()->make([
            SourceProductType::PARAM_CODE => 'resource-relation-type',
            SourceProductType::PARAM_NAME => 'Resource relation type',
        ]));
        $data = (new SourceProductResource($sourceProduct))->resolve(new Request);
        $this->assertArrayHasKey('source_product_type', $data);
        $this->assertArrayNotHasKey('user_created', $data);
    }

    public function test_paginate_resource_exposes_pagination_meta_and_links(): void
    {
        $paginator = new LengthAwarePaginator(
            collect([Author::factory()->make()]),
            1,
            15,
            1
        );

        $data = (new PaginateResource($paginator, AuthorResource::class))->resolve(new Request);

        $this->assertSame(['items', 'links', 'meta'], array_keys($data));
        $this->assertSame(['path', 'first_page_url', 'last_page_url', 'next_page_url', 'prev_page_url'], array_keys($data['links']));
        $this->assertSame([
            'current_page', 'per_page', 'from', 'last_page', 'to', 'total', 'count',
        ], array_keys($data['meta']));
        $this->assertSame(1, $data['meta']['count']);
    }

    private static function bookAttributes(): array
    {
        return [
            Book::PARAM_BINDING_TYPE_ID => 1,
            Book::PARAM_PAPER_TYPE_ID => 1,
            Book::PARAM_PUBLISHING_HOUSE_ID => 1,
            Book::PARAM_PUBLISHING_BRAND_ID => 1,
            Book::PARAM_BOOK_SERIES_ID => 1,
            Book::PARAM_USER_CREATED_ID => 1,
        ];
    }

    private static function productAttributes(): array
    {
        return [
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_CITY_ID => null,
            Product::PARAM_USER_CREATED_ID => 1,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ];
    }

    private static function sourceProductAttributes(): array
    {
        return [
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => 1,
            SourceProduct::PARAM_USER_CREATED_ID => 1,
        ];
    }

    private function makeBook(): Book
    {
        return Book::factory()->make(self::bookAttributes());
    }

    private function makeProduct(array $attributes = []): Product
    {
        return Product::factory()->make(array_merge(self::productAttributes(), $attributes));
    }

    private function makeSourceProduct(): SourceProduct
    {
        return SourceProduct::factory()->make(self::sourceProductAttributes());
    }
}
