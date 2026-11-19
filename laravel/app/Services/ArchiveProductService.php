<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductUserData;
use App\Repositories\ProductRepository;

readonly class ArchiveProductService
{
    public const string REQUIRE_TO_CREATE = 'require_to_create';

    public function __construct(
        private ProductRepository $productRepo,
    ) {}

    public function process(array $data): Product|bool
    {
        $product = $this->productRepo->findByShopProductId($data[Product::PARAM_SHOP_PRODUCT_ID]);

        if (! $product) {
            return false;
        }

        $product->productUserData()->update([
            ProductUserData::PARAM_IS_ARCHIVE => (bool) $data[ProductUserData::PARAM_IS_ARCHIVE],
        ]);

        return $product->load('productUserData');
    }
}
