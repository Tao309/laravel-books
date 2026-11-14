<?php

namespace App\Services;

use App\DTOs\BookUserDataDTO;
use App\DTOs\SourceProductUserDataDTO;
use App\Exceptions\ApiException;
use App\Models\BookUserData;
use App\Models\Product;
use App\Models\SourceProductUserData;
use App\Repositories\BookRepository;
use App\Repositories\BookUserDataRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SourceProductRepository;
use App\Repositories\SourceProductUserDataRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

readonly class LinkToProductService
{
    public function __construct(
        private ProductRepository $productRepo,
        private BookRepository $bookRepo,
        private SourceProductRepository $spRepository,
        private BookUserDataRepository $budRepo,
        private SourceProductUserDataRepository $spudRepo,
    ) {}

    public function linkBook(int $productId, int $bookId): Product
    {
        $product = $this->productRepo->get($productId);

        if ($product->source) {
            throw new ApiException('Product already has Book or SourceProduct');
        }

        try {
            $book = $this->bookRepo->get($bookId);
        } catch (ModelNotFoundException) {
            throw new ApiException('Not found Book by id = '.$bookId);
        }

        if (! $book->bookUserData) {
            $book->setRelation(
                'bookUserData',
                $this->budRepo->create($book->id, BookUserDataDTO::fromArray([
                    BookUserData::PARAM_BOOK_ID => $book->id,
                ]))
            );
        }

        $product->source()->associate($book)->save();

        return $product;
    }

    public function linkSourceProduct(int $productId, int $sourceProductId): Product
    {
        $product = $this->productRepo->get($productId);

        if ($product->source) {
            throw new ApiException('Product already has Book or SourceProduct');
        }

        try {
            $sourceProduct = $this->spRepository->get($sourceProductId);
        } catch (ModelNotFoundException) {
            throw new ApiException('Not found SourceProduct by id = '.$sourceProductId);
        }

        if (! $sourceProduct->sourceProductUserData) {
            $sourceProduct->setRelation(
                'sourceProductUserData',
                $this->spudRepo->create($sourceProduct->id, SourceProductUserDataDTO::fromArray([
                    SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
                ]))
            );
        }

        $product->source()->associate($sourceProduct)->save();

        return $product;
    }
}
