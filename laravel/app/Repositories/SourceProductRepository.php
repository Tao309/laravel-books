<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\DTOs\SourceProductUserDataDTO;
use App\Http\QueryFilters\SourceProductQueryFilter;
use App\Models\SourceProduct;
use App\Models\SourceProductUserData;
use App\Repositories\Interfaces\RepositoryInterface;
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

        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $entity = SourceProduct::newModelInstance($dto->getEntityData());

            $entity->sourceProductType()->associate(Arr::get($relationData, SourceProduct::PARAM_SOURCE_PRODUCT_TYPE));
            $entity->userCreated()->associate($userId);

            $entity->save();

            $this->spudRepository->create(
                $entity->id,
                SourceProductUserDataDTO::fromArray([
                    SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $entity->id,
                    ...Arr::get($relationData, SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA, []),
                ])
            );

            DB::commit();

            return SourceProduct::findOrFail($entity->id);
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function get(int $id): SourceProduct
    {
        return SourceProduct::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return $this->queryFilter->apply(
            SourceProduct::query(),
            $query->getDTO()
        )->get();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return $this->queryFilter->apply(
            SourceProduct::query(),
            $query->getDTO()
        )->paginate();
    }

    public function update(int $id, DtoInterface $dto): SourceProduct
    {
        $relationData = $dto->getRelationsData();

        DB::beginTransaction();

        try {
            $entity = SourceProduct::findOrFail($id);

            $entity->sourceProductType()->dissociate();
            $entity->sourceProductType()->associate(Arr::get($relationData, SourceProduct::PARAM_SOURCE_PRODUCT_TYPE));

            $entity->update($dto->getEntityData());

            $this->spudRepository->update(
                $entity->id,
                SourceProductUserDataDTO::fromArray([
                    SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $entity->id,
                    ...Arr::get($relationData, SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA, []),
                ])
            );

            DB::commit();

            return SourceProduct::findOrFail($entity->id);
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function delete($id): bool
    {
        $entity = SourceProduct::query()->withoutEagerLoads()->findOrFail($id);

        return $entity->delete();
    }
}
