<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Http\Resources\Products\MassSaveResourceCollection;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

class MassSaveProductsService
{
    public function __construct(
        private ProductRepository $productRepo,
    ) {}

    public function process(array $items): MassSaveResourceCollection
    {
        $productsCount = 0;
        $errorsCount = 0;
        $ids = [];
        $errorMessages = [];
        $products = [];

        foreach (array_chunk($items, 10) as $chunk) {
            try {
                $result = DB::transaction(function () use ($chunk): array {
                    $chunkIds = [];
                    $chunkProducts = [];

                    foreach ($chunk as $data) {
                        $dto = ProductDTO::fromArray($data);

                        if (! empty($data[Product::PARAM_ID])) {
                            $product = $this->productRepo->update($data[Product::PARAM_ID], $dto);
                        } else {
                            $product = $this->productRepo->create($dto);
                        }

                        $chunkIds[] = $product->id;
                        $chunkProducts[] = $product;
                    }

                    return ['ids' => $chunkIds, 'products' => $chunkProducts];
                });

                $productsCount += count($chunk);
                $ids = array_merge($ids, $result['ids']);
                $products = array_merge($products, $result['products']);
            } catch (\Throwable $e) {
                $productsCount += count($chunk);
                $errorsCount++;
                $errorMessages[] = $e->getMessage();
            }
        }

        return MassSaveResourceCollection::make($products)->additional([
            'products_count' => $productsCount,
            'errors_count' => $errorsCount,
            'saved_count' => count($ids),
            'error_messages' => $errorMessages,
        ]);
    }
}
