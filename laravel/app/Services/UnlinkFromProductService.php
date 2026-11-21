<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Book;
use App\Models\Product;
use App\Models\SourceProduct;
use App\Repositories\BookRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SourceProductRepository;
use Illuminate\Support\Facades\DB;

readonly class UnlinkFromProductService
{
    public function __construct(
        private BookRepository $bookRepo,
        private SourceProductRepository $spRepository,
        private ProductRepository $productRepo,
    ) {}

    public function process(int $productId): Product
    {
        $product = $this->productRepo->get($productId);

        $book = $product->source instanceof Book ? $product->source : null;
        $sourceProduct = $product->source instanceof SourceProduct ? $product->source : null;

        if (! $product->source) {
            throw new ApiException('Product already no has Book or SourceProduct');
        }

        DB::beginTransaction();

        try {
            $product->source()->dissociate()->save();

            if ($book) {
                $this->bookRepo->updateOnSailCount($book);
            } elseif ($sourceProduct) {
                $this->spRepository->updateOnSailCount($sourceProduct);
            }

            DB::Commit();

            return $product;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
