<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\DTOs\ProductDTO;
use App\DTOs\ProductUserDataDTO;
use App\Enums\ProductFlag;
use App\Http\QueryFilters\ProductQueryFilter;
use App\Models\Book;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\ProductUserData;
use App\Models\SourceProduct;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Services\DateService;
use App\Services\SameProductsLoader;
use App\Services\SessionShopService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductRepository implements RepositoryInterface
{
    private const RELATIONS = [
        Product::PARAM_SOURCE,
        Product::PARAM_CITY,
        'productUserData',
        Product::PARAM_PRICES,
        Product::PARAM_STOCKS,
    ];

    public function __construct(
        private readonly SameProductsLoader $sameProductsLoader,
        private readonly DateService $dateService,
        private readonly ProductPriceRepository $ppRepository,
        private readonly ProductStockRepository $psRepository,
        private readonly ProductUserDataRepository $pudRepo,
        private readonly ProductQueryFilter $queryFilter,
    ) {}

    /**
     * @param  ProductDTO  $dto
     */
    public function create(DtoInterface $dto): Product
    {
        $relationData = $dto->getRelationsData();

        DB::beginTransaction();
        try {
            $userId = Auth::id();

            $entity = Product::newModelInstance($dto->getEntityData());

            $this->setSourceToModel($entity, $relationData);

            $entity->shop()->associate(Arr::get($relationData, Product::PARAM_SHOP));

            if (Arr::has($relationData, Product::PARAM_CITY)) {
                $entity->city()->associate(Arr::get($relationData, Product::PARAM_CITY));
            }

            $entity->userCreated()->associate($userId);

            $entity->save();

            $this->pudRepo->create(
                $entity->id,
                ProductUserDataDTO::fromArray([
                    ProductUserData::PARAM_PRODUCT_ID => $entity->id,
                    ...Arr::get($relationData, Product::PARAM_PRODUCT_USER_DATA, []),
                ])
            );

            $this->savePrices($entity, $dto);
            $this->saveStocks($entity, $dto);

            DB::commit();

            $entity = $this->query()->findOrFail($entity->id);

            $this->sameProductsLoader->load($entity);

            return $entity;
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function get(int $id): Product
    {
        $product = $this->query()->where([
            Product::PARAM_ID => $id,
            Product::PARAM_SHOP_ID => SessionShopService::getShopId(),
        ])->first();

        if (! $product) {
            throw new ModelNotFoundException("Product by id = {$id} not found");
        }

        $this->sameProductsLoader->load($product);

        return $product;
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        $products = $this->queryFilter->apply(
            $this->query(),
            $query->getDTO()
        )->get();

        $this->sameProductsLoader->load($products);

        return $products;
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        $paginator = $this->queryFilter->apply(
            $this->query(),
            $query->getDTO()
        )->paginate();

        /** @var Collection<int, Product> $products */
        $products = $paginator->getCollection();

        $this->sameProductsLoader->load($products);

        return $paginator;
    }

    /**
     * @param  ProductDTO  $dto
     */
    public function update(int $id, DtoInterface $dto): Product
    {
        $relationData = $dto->getRelationsData();

        DB::beginTransaction();
        try {
            $entity = Product::findOrFail($id);

            $this->setSourceToModel($entity, $relationData);

            if (Arr::has($relationData, Product::PARAM_SHOP)) {
                $entity->shop()->associate(Arr::get($relationData, Product::PARAM_SHOP));
            }

            if (Arr::has($relationData, Product::PARAM_CITY)) {
                $entity->city()->associate(Arr::get($relationData, Product::PARAM_CITY));
            }

            $entity->update($dto->getEntityData());

            $pudData = Arr::get($relationData, Product::PARAM_PRODUCT_USER_DATA, []);

            if (ProductFlag::SaveProductUserData->isEnabled($dto->flags)) {
                $this->pudRepo->update(
                    $entity->id,
                    ProductUserDataDTO::fromArray([
                        ProductUserData::PARAM_PRODUCT_ID => $entity->id,
                        ...$pudData,
                    ])
                );
            }

            $this->savePrices($entity, $dto);
            $this->saveStocks($entity, $dto);

            DB::commit();

            $product = $this->query()->findOrFail($entity->id);

            $this->sameProductsLoader->load($product);

            return $product;
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $entity = $this->query()->withoutEagerLoads()->findOrFail($id);

        return $entity->delete();
    }

    /**
     * Запрос для дальнейших действий, не грузим всю модель
     */
    public function findByShopProduct(string|int $shopProductId): ?Product
    {
        return Product::query()
            ->select([Product::PARAM_ID, Product::PARAM_SHOP_ID])
            ->where(Product::PARAM_SHOP_PRODUCT_ID, $shopProductId)
            ->where(Product::PARAM_SHOP_ID, SessionShopService::getShopId())
            ->firstOrFail();
    }

    /**
     * @return Builder<Product>
     */
    private function query(): Builder
    {
        return Product::query()->with(self::RELATIONS);
    }

    private function setSourceToModel(Product $entity, array $data): void
    {
        $sourceType = $sourceId = null;

        if (! empty($data[Product::PARAM_BOOK])) {
            $sourceType = Book::class;
            $sourceId = $data[Product::PARAM_BOOK];
        } elseif (! empty($data[Product::PARAM_SOURCE_PRODUCT])) {
            $sourceType = SourceProduct::class;
            $sourceId = $data[Product::PARAM_SOURCE_PRODUCT];
        }

        if (empty($sourceId) || empty($sourceType)) {
            return;
        }

        $entity->source_type = $sourceType;
        $entity->source_id = $sourceId;
    }

    /**
     * @param  ProductDTO  $dto
     */
    private function savePrices(Product $product, DtoInterface $dto): void
    {
        if (! ProductFlag::SavePrices->isEnabled($dto->flags)) {
            return;
        }

        $dtoPrices = $dto->prices;

        // Проверка, если последняя цена из массива больше или равно minPrice то stop если уже есть цены
        if ($product->prices->count()) {
            $minPrice = $product->minPrice();

            usort(
                $dtoPrices,
                fn (array $a, array $b) => $b[ProductPrice::PARAM_DATE] <=> $a[ProductPrice::PARAM_DATE]
            );

            $latestPrice = reset($dtoPrices) ?: null;

            if ($minPrice && $latestPrice && $latestPrice[ProductPrice::PARAM_PRICE] >= $minPrice) {
                return;
            }
        }

        $this->ppRepository->massSave(
            $product->id,
            $dtoPrices
        );
    }

    /**
     * @param  ProductDTO  $dto
     */
    private function saveStocks(Product $product, DtoInterface $dto): void
    {
        if (! ProductFlag::SaveStocks->isEnabled($dto->flags)) {
            return;
        }

        $dtoStocks = $dto->stocks;

        if ($product->stocks->count()) {
            usort(
                $dtoStocks,
                fn (array $a, array $b) => $b[ProductStock::PARAM_DATE] <=> $a[ProductStock::PARAM_DATE]
            );

            $lastStock = reset($dtoStocks) ?: null;
            $productLastStock = $product->lastStock();

            $isLastStockEqualsQty = $lastStock && $productLastStock
                && $productLastStock->date->format('d.m.Y')
                    === $this->dateService->getDateTime($lastStock[ProductStock::PARAM_DATE])->format('d.m.Y')
                && $productLastStock->qty === $lastStock[ProductStock::PARAM_QTY];

            if ($isLastStockEqualsQty) {
                return;
            }
        }

        $this->psRepository->massSave(
            $product->id,
            $dto->stocks
        );
    }
}
