<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\DTOs\ProductUserDataDTO;
use App\Http\QueryFilters\ProductQueryFilter;
use App\Models\Book;
use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\SourceProduct;
use App\Repositories\Interfaces\RepositoryInterface;
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
        private readonly ProductPriceRepository $ppRepository,
        private readonly ProductStockRepository $psRepository,
        private readonly ProductUserDataRepository $pudRepo,
        private readonly ProductQueryFilter $queryFilter,
    ) {}

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

            $this->ppRepository->massSave(
                $entity->id,
                Arr::get($relationData, Product::PARAM_PRICES, [])
            );

            $this->psRepository->massSave(
                $entity->id,
                Arr::get($relationData, Product::PARAM_STOCKS, [])
            );

            DB::commit();

            return $this->query()->findOrFail($entity->id);
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

        return $product;
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return $this->queryFilter->apply(
            $this->query(),
            $query->getDTO()
        )->get();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return $this->queryFilter->apply(
            $this->query(),
            $query->getDTO()
        )->paginate();
    }

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

            if (! empty($pudData)) {
                $this->pudRepo->update(
                    $entity->id,
                    ProductUserDataDTO::fromArray([
                        ProductUserData::PARAM_PRODUCT_ID => $entity->id,
                        ...$pudData,
                    ])
                );
            }

            $this->ppRepository->massSave(
                $entity->id,
                Arr::get($relationData, Product::PARAM_PRICES, [])
            );

            $this->psRepository->massSave(
                $entity->id,
                Arr::get($relationData, Product::PARAM_STOCKS, [])
            );

            DB::commit();

            return $this->query()->findOrFail($entity->id);
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
     * Для дальнейших действий, не грузим всю модели
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
}
