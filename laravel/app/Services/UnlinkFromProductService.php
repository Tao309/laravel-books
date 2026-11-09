<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Product;
use App\Repositories\ProductRepository;

readonly class UnlinkFromProductService
{
    public function __construct(
        private ProductRepository $productRepo,
    ) {}

    public function unlink(int $productId): Product
    {
        $product = $this->productRepo->get($productId);

        if (! $product->source) {
            throw new ApiException('Product already no has Book or SourceProduct');
        }

        $product->source()->dissociate()->save();

        return $product;
    }
}
