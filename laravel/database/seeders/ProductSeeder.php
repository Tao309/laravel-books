<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->getUsers();
        $shops = $this->getShops();
        $books = $this->getBooks();
        $sourceProducts = $this->getSourceProducts();

        $sp = $sourceProducts->filter(function (SourceProduct $sourceProduct) {
            return $sourceProduct->title === 'Куртка зимняя';
        })->firstOrFail();

        Product::create([
            Product::PARAM_SHOP_PRODUCT_ID => '6055250413',
            Product::PARAM_TITLE => 'Куртка Зимняя',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),

            Product::PARAM_SOURCE.'_type' => SourceProduct::class,
            Product::PARAM_SOURCE.'_id' => $sp->id,

            Product::PARAM_SHOP_ID => $shops->filter(function (Shop $shop) {
                return $shop->type === 'ozon';
            })->firstOrFail()->id,

            Product::PARAM_USER_CREATED_ID => $users->firstOrFail()->id,
        ]);

        $book = $books->filter(function (Book $book) {
            return $book->title === 'Ловец Чудес';
        })->firstOrFail();

        Product::create([
            Product::PARAM_SHOP_PRODUCT_ID => '6226491440',
            Product::PARAM_TITLE => 'Ловец Чудес #1',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),

            Product::PARAM_SOURCE.'_type' => Book::class,
            Product::PARAM_SOURCE.'_id' => $book->id,

            Product::PARAM_SHOP_ID => $shops->filter(function (Shop $shop) {
                return $shop->type === 'ozon';
            })->firstOrFail()->id,

            Product::PARAM_USER_CREATED_ID => $users->firstOrFail()->id,
        ]);

        $book = $books->filter(function (Book $book) {
            return $book->title === 'Ловец Чудес';
        })->firstOrFail();

        Product::create([
            Product::PARAM_SHOP_PRODUCT_ID => '3300252850',
            Product::PARAM_SHOP_PRODUCT_CODE => '1894528868',
            Product::PARAM_TITLE => 'Ловец Чудес #2',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),

            Product::PARAM_SOURCE.'_type' => Book::class,
            Product::PARAM_SOURCE.'_id' => $book->id,

            Product::PARAM_SHOP_ID => $shops->filter(function (Shop $shop) {
                return $shop->type === 'wildberries';
            })->firstOrFail()->id,

            Product::PARAM_USER_CREATED_ID => $users->firstOrFail()->id,
        ]);

        $book = $books->filter(function (Book $book) {
            return $book->title === 'Хтонь за околицей';
        })->firstOrFail();

        Product::create([
            Product::PARAM_SHOP_PRODUCT_ID => '3315320967',
            Product::PARAM_SHOP_PRODUCT_CODE => '1903147507',
            Product::PARAM_TITLE => 'Хтонь за околицей. Книга первая',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),

            Product::PARAM_SOURCE.'_type' => Book::class,
            Product::PARAM_SOURCE.'_id' => $book->id,

            Product::PARAM_SHOP_ID => $shops->filter(function (Shop $shop) {
                return $shop->type === 'wildberries';
            })->firstOrFail()->id,

            Product::PARAM_USER_CREATED_ID => $users->firstOrFail()->id,
        ]);
    }

    private function getBooks(): Collection
    {
        return Book::all();
    }

    private function getSourceProducts(): Collection
    {
        return SourceProduct::all();
    }

    private function getShops(): Collection
    {
        return Shop::all();
    }

    private function getUsers(): Collection
    {
        return User::all();
    }
}
