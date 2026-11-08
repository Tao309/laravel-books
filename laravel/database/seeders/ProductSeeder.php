<?php

namespace Database\Seeders;

use App\Auth\CustomUserProvider;
use App\DTOs\ProductDTO;
use App\Models\Book;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\ProductUserData;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Repositories\ProductRepository;
use App\Services\DateService;
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
            Product::PARAM_PRODUCT_USER_DATA => [
                ProductUserData::PARAM_AVAILABLE => false,
                ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM => $this->createDate('2026.11.01'),
                ProductUserData::PARAM_AVAILABLE_DATE_FROM => $this->createDate('2026.11.07'),
                ProductUserData::PARAM_LISTEN_PRICE_VALUE => 5400,
                ProductUserData::PARAM_LISTEN_QTY_VALUE => 4,
                ProductUserData::PARAM_RELEASE_DATE => null,
                ProductUserData::PARAM_IS_ARCHIVE => false,
                ProductUserData::PARAM_COMMENT => 'Комментарий для: Куртка Зимняя',
            ],
            Product::PARAM_PRICES => [
                [
                    ProductPrice::PARAM_PRICE => 800,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.11.01'),
                ],
                [
                    ProductPrice::PARAM_PRICE => 700,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.11.02'),
                ],
                [
                    ProductPrice::PARAM_PRICE => 600,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.11.03'),
                ],
                [
                    ProductPrice::PARAM_PRICE => 500,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.11.04'),
                ],
            ],
            Product::PARAM_STOCKS => [
                [
                    ProductStock::PARAM_QTY => 100,
                    ProductStock::PARAM_DATE => $this->createDate('2026.11.01'),
                ],
                [
                    ProductStock::PARAM_QTY => 80,
                    ProductStock::PARAM_DATE => $this->createDate('2026.11.02'),
                ],
                [
                    ProductStock::PARAM_QTY => 40,
                    ProductStock::PARAM_DATE => $this->createDate('2026.11.03'),
                ],
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
            Product::PARAM_PRODUCT_USER_DATA => [
                ProductUserData::PARAM_AVAILABLE => true,
                ProductUserData::PARAM_LISTEN_PRICE_VALUE => 650,
                ProductUserData::PARAM_LISTEN_QTY_VALUE => 20,
                ProductUserData::PARAM_RELEASE_DATE => $this->createDate('2026.09.14'),
                ProductUserData::PARAM_COMMENT => 'Комментарий для: Ловец Чудес. #1',
            ],
            Product::PARAM_PRICES => [
                [
                    ProductPrice::PARAM_PRICE => 1200,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.10.11'),
                ],
                [
                    ProductPrice::PARAM_PRICE => 1000,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.10.15'),
                ],
                [
                    ProductPrice::PARAM_PRICE => 800,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.10.21'),
                ],
            ],
            Product::PARAM_STOCKS => [
                [
                    ProductStock::PARAM_QTY => 6,
                    ProductStock::PARAM_DATE => $this->createDate('2026.10.07'),
                ],
                [
                    ProductStock::PARAM_QTY => 5,
                    ProductStock::PARAM_DATE => $this->createDate('2026.10.14'),
                ],
                [
                    ProductStock::PARAM_QTY => 2,
                    ProductStock::PARAM_DATE => $this->createDate('2026.10.23'),
                ],
            ],
        ]));

        $book = $books->filter(function (Book $book) {
            return $book->title === 'Ловец Чудес';
        })->firstOrFail();

        $this->repo->create(ProductDTO::fromArray([
            Product::PARAM_SHOP_PRODUCT_ID => '3300252850',
            Product::PARAM_SHOP_PRODUCT_CODE => '1894528868',
            Product::PARAM_TITLE => 'Ловец Чудес #2 (без цен и стоков)',

            Product::PARAM_BOOK => [
                Book::PARAM_ID => $book->id,
            ],

            Product::PARAM_SHOP => [
                Shop::PARAM_ID => $shops->filter(function (Shop $shop) {
                    return $shop->type === 'wildberries';
                })->firstOrFail()->id,
            ],
            Product::PARAM_PRODUCT_USER_DATA => [
                ProductUserData::PARAM_AVAILABLE => true,
                ProductUserData::PARAM_AVAILABLE_DATE_FROM => $this->createDate('2026.08.12'),
                ProductUserData::PARAM_LISTEN_PRICE_VALUE => 580,
                ProductUserData::PARAM_LISTEN_QTY_VALUE => 10,
                ProductUserData::PARAM_RELEASE_DATE => $this->createDate('2026.09.26'),
                ProductUserData::PARAM_COMMENT => 'Комментарий для: Ловец Чудес #2 (без цен и стоков)',
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
            Product::PARAM_PRODUCT_USER_DATA => [
                ProductUserData::PARAM_AVAILABLE => true,
                ProductUserData::PARAM_LISTEN_PRICE_VALUE => 450,
                ProductUserData::PARAM_LISTEN_QTY_VALUE => 10,
                ProductUserData::PARAM_IS_ARCHIVE => false,
                ProductUserData::PARAM_COMMENT => 'Комментарий для: Хтонь за околицей. Книга первая',
            ],
            Product::PARAM_PRICES => [
                [
                    ProductPrice::PARAM_PRICE => 20000,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.05.10'),
                ],
                [
                    ProductPrice::PARAM_PRICE => 19900,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.08.22'),
                ],
                [
                    ProductPrice::PARAM_PRICE => 18900,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.10.14'),
                ],
            ],
            Product::PARAM_STOCKS => [
                [
                    ProductStock::PARAM_QTY => 1420,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.05.10'),
                ],
                [
                    ProductStock::PARAM_QTY => 640,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.08.28'),
                ],
                [
                    ProductStock::PARAM_QTY => 520,
                    ProductPrice::PARAM_DATE => $this->createDate('2026.09.15'),
                ],
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

    private function createDate(string $date): string
    {
        return DateService::createFromDate($date, 'Y.m.d');
    }
}
