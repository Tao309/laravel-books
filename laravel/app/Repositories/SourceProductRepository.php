<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\CQRS\Queries\SourceProducts\GetSourceProductsCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\DTOs\SourceProductUserDataDTO;
use App\Http\QueryFilters\SourceProductQueryFilter;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SourceProduct;
use App\Models\SourceProductUserData;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SourceProductRepository implements RepositoryInterface
{
    public function __construct(
        private readonly SourceProductUserDataRepository $spudRepository,
        private readonly SourceProductQueryFilter $queryFilter,
    ) {}

    public function create(DtoInterface $dto): SourceProduct
    {
        $relationData = $dto->getRelationsData();

        $id = DB::transaction(function () use ($dto, $relationData): int {
            $userId = Auth::id();
            $entity = SourceProduct::newModelInstance($dto->getEntityData());

            $entity->sourceProductType()->associate(Arr::get($relationData, SourceProduct::PARAM_SOURCE_PRODUCT_TYPE));
            $entity->brand()->associate(Arr::get($relationData, SourceProduct::PARAM_BRAND));
            $entity->userCreated()->associate($userId);

            $entity->save();

            $this->spudRepository->create(
                $entity->id,
                SourceProductUserDataDTO::fromArray([
                    SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $entity->id,
                    ...Arr::get($relationData, SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA, []),
                ])
            );

            return $entity->id;
        });

        return $this->query()->findOrFail($id);
    }

    public function get(int $id): SourceProduct
    {
        return SourceProduct::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        /**
         * @var $query GetSourceProductsCollectionQueryInterface
         */

        return $this->queryFilter->apply(
            SourceProduct::query(),
            $query->getDTO()
        )->get();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        /**
         * @var $query GetSourceProductsCollectionQueryInterface
         */

        return $this->queryFilter->apply(
            SourceProduct::query(),
            $query->getDTO()
        )->paginate();
    }

    public function update(int $id, DtoInterface $dto): SourceProduct
    {
        $entity = SourceProduct::findOrFail($id);
        $relationData = $dto->getRelationsData();

        $id = DB::transaction(function () use ($entity, $dto, $relationData): int {
            $entity->sourceProductType()->associate(Arr::get($relationData, SourceProduct::PARAM_SOURCE_PRODUCT_TYPE));
            $entity->brand()->associate(Arr::get($relationData, SourceProduct::PARAM_BRAND));

            $entity->update($dto->getEntityData());

            $spudData = Arr::get($relationData, SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA, []);

            if (! empty($spudData)) {
                $this->spudRepository->update(
                    $entity->id,
                    SourceProductUserDataDTO::fromArray([
                        SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $entity->id,
                        ...$spudData,
                    ])
                );
            }

            return $entity->id;
        });

        return $this->query()->findOrFail($id);
    }

    public function delete(int $id): bool
    {
        $entity = SourceProduct::query()->withoutEagerLoads()->findOrFail($id);

        return $entity->delete();
    }

    public function updateOnSailCount(SourceProduct $sourceProduct): void
    {
        $totalQty = (int) $sourceProduct->products()
            ->join(
                sprintf('%s as ps', ProductStock::TABLE_NAME),
                sprintf('ps.%s', ProductStock::PARAM_PRODUCT_ID),
                '=',
                sprintf('%s.%s', Product::TABLE_NAME, Product::PARAM_ID),
            )
            ->where(sprintf('ps.%s', ProductStock::PARAM_USER_ID), Auth::id())
            ->sum(sprintf('ps.%s', ProductStock::PARAM_QTY));

        $sourceProduct->update([
            SourceProduct::PARAM_ON_SALE_COUNT => $totalQty,
        ]);
    }

    /**
     * @return Builder<SourceProduct>
     */
    private function query(): Builder
    {
        return SourceProduct::query();
    }
}
