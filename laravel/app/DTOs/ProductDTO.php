<?php

namespace App\DTOs;

use App\Models\City;
use App\Models\Product;
use App\Models\Shop;
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
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            shopProductId: $data[Product::PARAM_SHOP_PRODUCT_ID],
            shopProductCode: $data[Product::PARAM_SHOP_PRODUCT_CODE] ?? null,
            title: $data[Product::PARAM_TITLE],
            shop: $data[Product::PARAM_SHOP],
            city: $data[Product::PARAM_CITY] ?? null,
            book: $data[Product::PARAM_BOOK] ?? null,
            sourceProduct: $data[Product::PARAM_SOURCE_PRODUCT] ?? null,
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
            Product::PARAM_CITY => $this->city[City::PARAM_ID] ?? null,
            Product::PARAM_BOOK => $this->book[City::PARAM_ID] ?? null,
            Product::PARAM_SOURCE_PRODUCT => $this->sourceProduct[City::PARAM_ID] ?? null,
        ];
    }
}
