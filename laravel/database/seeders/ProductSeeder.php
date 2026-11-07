<?php

namespace Database\Seeders;

use App\Auth\CustomUserProvider;
use App\DTOs\ProductDTO;
use App\Models\Book;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ProductSeeder extends AbstractSeeder
{
    public function __construct(
        private readonly ProductRepository $repo,
    ) {}

    public function run(): void
    {
        $user = $this->getFirstUser();
        Auth::attempt([
            CustomUserProvider::TOKEN => $user->remember_token,
        ], true);

        $shops = $this->getShops();
        $books = $this->getBooks();
        $sourceProducts = $this->getSourceProducts();

        $sp = $sourceProducts->filter(function (SourceProduct $sourceProduct) {
            return $sourceProduct->title === 'Куртка зимняя';
        })->firstOrFail();

        $this->repo->create(ProductDTO::fromArray([
            Product::PARAM_SHOP_PRODUCT_ID => '6055250413',
            Product::PARAM_TITLE => 'Куртка Зимняя',

            Product::PARAM_SOURCE_PRODUCT => [
                SourceProduct::PARAM_ID => $sp->id,
            ],
            Product::PARAM_SHOP => [
                Shop::PARAM_ID => $shops->filter(function (Shop $shop) {
                    return $shop->type === 'ozon';
                })->firstOrFail()->id,
            ],
        ]));

        $book = $books->filter(function (Book $book) {
            return $book->title === 'Ловец Чудес';
        })->firstOrFail();

        $this->repo->create(ProductDTO::fromArray([
            Product::PARAM_SHOP_PRODUCT_ID => '6226491440',
            Product::PARAM_TITLE => 'Ловец Чудес. #1',

            Product::PARAM_BOOK => [
                Book::PARAM_ID => $book->id,
            ],
            Product::PARAM_SHOP => [
                Shop::PARAM_ID => $shops->filter(function (Shop $shop) {
                    return $shop->type === 'ozon';
                })->firstOrFail()->id,
            ],
        ]));

        $book = $books->filter(function (Book $book) {
            return $book->title === 'Ловец Чудес';
        })->firstOrFail();

        $this->repo->create(ProductDTO::fromArray([
            Product::PARAM_SHOP_PRODUCT_ID => '3300252850',
            Product::PARAM_SHOP_PRODUCT_CODE => '1894528868',
            Product::PARAM_TITLE => 'Ловец Чудес #2',

            Product::PARAM_BOOK => [
                Book::PARAM_ID => $book->id,
            ],

            Product::PARAM_SHOP => [
                Shop::PARAM_ID => $shops->filter(function (Shop $shop) {
                    return $shop->type === 'wildberries';
                })->firstOrFail()->id,
            ],
        ]));

        $book = $books->filter(function (Book $book) {
            return $book->title === 'Хтонь за околицей';
        })->firstOrFail();

        $this->repo->create(ProductDTO::fromArray([
            Product::PARAM_SHOP_PRODUCT_ID => '3315320967',
            Product::PARAM_SHOP_PRODUCT_CODE => '1903147507',
            Product::PARAM_TITLE => 'Хтонь за околицей. Книга первая',

            Product::PARAM_BOOK => [
                Book::PARAM_ID => $book->id,
            ],

            Product::PARAM_SHOP => [
                Shop::PARAM_ID => $shops->filter(function (Shop $shop) {
                    return $shop->type === 'wildberries';
                })->firstOrFail()->id,
            ],
        ]));
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
}
