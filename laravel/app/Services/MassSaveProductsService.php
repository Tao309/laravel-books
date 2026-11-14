<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Http\Resources\Products\ProductsResourceCollection;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

readonly class MassSaveProductsService
{
    public function __construct(
        private ProductRepository $productRepo,
    ) {}

    public function process(array $items): array
    {
        $productsCount = 0;
        $errorsCount = 0;
        $ids = [];
        $errorMessages = [];
        $products = [];

        foreach (array_chunk($items, 10) as $chunk) {
            DB::beginTransaction();

            try {
                foreach ($chunk as $data) {
                    $productsCount++;

                    $dto = ProductDTO::fromArray($data);

                    if (! empty($data[Product::PARAM_ID])) {
                        $product = $this->productRepo->update($data[Product::PARAM_ID], $dto);
                    } else {
                        $product = $this->productRepo->create($dto);
                    }

                    $ids[] = $product->id;
                    $products[] = $product;
                }

                DB::commit();
                $ids = [];
                $products = [];
            } catch (\Throwable $e) {
                $errorsCount++;
                $errorMessages[] = $e->getMessage();

                DB::rollBack();
            }
        }

        return [
            'products_count' => $productsCount,
            'errors_count' => $errorsCount,
            'saved_count' => count($ids),
            'items' => ProductsResourceCollection::make($products),
            'error_messages' => $errorMessages,
        ];
    }
}
