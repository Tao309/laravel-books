<?php

namespace App\DTOs;

use App\Models\Book;
use App\Models\City;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SourceProduct;
use Illuminate\Support\Arr;

class ProductDTO extends AbstractDTO
{
    public function __construct(
        public string $shopProductId,
        public ?string $shopProductCode,
        public string $title,
        public array $shop,
        public ?array $city,
        public ?array $book,
        public ?array $sourceProduct,
        public ?array $productUserData,
        public ?array $prices,
        public ?array $stocks,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            shopProductId: $data[Product::PARAM_SHOP_PRODUCT_ID],
            shopProductCode: Arr::get($data, Product::PARAM_SHOP_PRODUCT_CODE),
            title: $data[Product::PARAM_TITLE],
            shop: $data[Product::PARAM_SHOP],
            city: Arr::get($data, Product::PARAM_CITY),
            book: Arr::get($data, Product::PARAM_BOOK),
            sourceProduct: Arr::get($data, Product::PARAM_SOURCE_PRODUCT, []),
            productUserData: Arr::get($data, Product::PARAM_PRODUCT_USER_DATA, []),
            prices: Arr::get($data, Product::PARAM_PRICES, []),
            stocks: Arr::get($data, Product::PARAM_STOCKS, []),
        );
    }

    public function getEntityData(): array
    {
        return [
            Product::PARAM_SHOP_PRODUCT_ID => $this->shopProductId,
            Product::PARAM_SHOP_PRODUCT_CODE => $this->shopProductCode,
            Product::PARAM_TITLE => $this->title,
        ];
    }

    public function getRelationsData(): array
    {
        return [
            Product::PARAM_SHOP => Arr::get($this->shop, Shop::PARAM_ID),
            Product::PARAM_CITY => Arr::get($this->city, City::PARAM_ID),
            Product::PARAM_BOOK => Arr::get($this->book, Book::PARAM_ID),
            Product::PARAM_SOURCE_PRODUCT => Arr::get($this->sourceProduct, SourceProduct::PARAM_ID),
            Product::PARAM_PRODUCT_USER_DATA => $this->productUserData,
            Product::PARAM_PRICES => $this->prices,
            Product::PARAM_STOCKS => $this->stocks,
        ];
    }
}
