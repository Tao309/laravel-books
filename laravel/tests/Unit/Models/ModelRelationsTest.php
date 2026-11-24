<?php

namespace Tests\Unit\Models;

use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ModelRelationsTest extends TestCase
{
    public static function relationProvider(): array
    {
        return [
            'author.books' => [Author::class, 'books', BelongsToMany::class, Book::class, [
                'pivot_table' => 'author_books',
                'foreign_pivot_key' => 'author_id',
                'related_pivot_key' => 'book_id',
            ]],
            'book.authors' => [Book::class, 'authors', BelongsToMany::class, Author::class, [
                'pivot_table' => 'author_books',
                'foreign_pivot_key' => 'book_id',
                'related_pivot_key' => 'author_id',
            ]],
            'book.bindingType' => [Book::class, 'bindingType', BelongsTo::class, BindingType::class, ['foreign_key' => 'binding_type_id']],
            'book.paperType' => [Book::class, 'paperType', BelongsTo::class, PaperType::class, ['foreign_key' => 'paper_type_id']],
            'book.publishingHouse' => [Book::class, 'publishingHouse', BelongsTo::class, PublishingHouse::class, ['foreign_key' => 'publishing_house_id']],
            'book.publishingBrand' => [Book::class, 'publishingBrand', BelongsTo::class, PublishingBrand::class, ['foreign_key' => 'publishing_brand_id']],
            'book.bookSeries' => [Book::class, 'bookSeries', BelongsTo::class, BookSeries::class, ['foreign_key' => 'book_series_id']],
            'book.userCreated' => [Book::class, 'userCreated', BelongsTo::class, User::class, ['foreign_key' => 'user_created_id']],
            'book.products' => [Book::class, 'products', MorphMany::class, Product::class, ['morph_type' => 'source_type']],
            'book.bookUserData' => [Book::class, 'bookUserData', HasOne::class, BookUserData::class, ['foreign_key' => 'book_id']],
            'bookUserData.book' => [BookUserData::class, 'book', BelongsTo::class, Book::class, ['foreign_key' => 'book_id']],
            'product.source' => [Product::class, 'source', MorphTo::class, null, ['morph_type' => 'source_type', 'foreign_key' => 'source_id']],
            'product.shop' => [Product::class, 'shop', BelongsTo::class, Shop::class, ['foreign_key' => 'shop_id']],
            'product.city' => [Product::class, 'city', BelongsTo::class, City::class, ['foreign_key' => 'city_id']],
            'product.userCreated' => [Product::class, 'userCreated', BelongsTo::class, User::class, ['foreign_key' => 'user_created_id']],
            'product.productUserData' => [Product::class, 'productUserData', HasOne::class, ProductUserData::class, ['foreign_key' => 'product_id']],
            'product.prices' => [Product::class, 'prices', HasMany::class, ProductPrice::class, ['foreign_key' => 'product_id']],
            'product.stocks' => [Product::class, 'stocks', HasMany::class, ProductStock::class, ['foreign_key' => 'product_id']],
            'productUserData.product' => [ProductUserData::class, 'product', BelongsTo::class, Product::class, ['foreign_key' => 'product_id']],
            'sourceProduct.sourceProductType' => [SourceProduct::class, 'sourceProductType', BelongsTo::class, SourceProductType::class, ['foreign_key' => 'source_product_type_id']],
            'sourceProduct.userCreated' => [SourceProduct::class, 'userCreated', BelongsTo::class, User::class, ['foreign_key' => 'user_created_id']],
            'sourceProduct.products' => [SourceProduct::class, 'products', MorphMany::class, Product::class, ['morph_type' => 'source_type']],
            'sourceProduct.sourceProductUserData' => [SourceProduct::class, 'sourceProductUserData', HasOne::class, SourceProductUserData::class, ['foreign_key' => 'source_product_id']],
            'sourceProductUserData.book' => [SourceProductUserData::class, 'book', BelongsTo::class, SourceProduct::class, ['foreign_key' => 'source_product_id']],
        ];
    }

    #[DataProvider('relationProvider')]
    public function test_relation_methods_return_expected_relation_instances(
        string $modelClass,
        string $method,
        string $relationClass,
        ?string $relatedClass,
        array $expectations,
    ): void {
        $model = new $modelClass;
        $this->assertTrue(method_exists($model, $method), "Missing relation {$modelClass}::{$method}()");

        $relation = $model->{$method}();
        $this->assertInstanceOf($relationClass, $relation);

        if ($relatedClass !== null) {
            $this->assertInstanceOf($relatedClass, $relation->getRelated());
        }

        if (isset($expectations['foreign_key'])) {
            $this->assertSame($expectations['foreign_key'], $relation->getForeignKeyName());
        }
        if (isset($expectations['local_key'])) {
            $this->assertSame($expectations['local_key'], $relation->getLocalKeyName());
        }
        if (isset($expectations['morph_type'])) {
            $this->assertSame($expectations['morph_type'], $relation->getMorphType());
        }
        if (isset($expectations['pivot_table'])) {
            $this->assertSame($expectations['pivot_table'], $relation->getTable());
            $this->assertSame($expectations['foreign_pivot_key'], $relation->getForeignPivotKeyName());
            $this->assertSame($expectations['related_pivot_key'], $relation->getRelatedPivotKeyName());
        }
    }

    public function test_models_without_relations_have_no_unexpected_relation_methods(): void
    {
        foreach ([BindingType::class, BookSeries::class, City::class, PaperType::class,
            PublishingBrand::class, PublishingHouse::class, Shop::class, SourceProductType::class, User::class] as $modelClass) {
            $model = new $modelClass;
            foreach (['books', 'authors', 'products', 'shop', 'city', 'userCreated', 'source'] as $method) {
                $this->assertFalse(method_exists($model, $method), "Unexpected {$modelClass}::{$method}()");
            }
        }
    }
}
