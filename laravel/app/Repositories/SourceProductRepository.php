<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\SourceProduct;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SourceProductRepository implements RepositoryInterface
{
    public function __construct(
        private readonly SourceProductUserDataRepository $spudRepository
    ) {}

    public function create(array $data): SourceProduct
    {
        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $entity = SourceProduct::newModelInstance([
                SourceProduct::PARAM_TITLE => $data[SourceProduct::PARAM_TITLE],
            ]);

            $entity->sourceProductType()->associate(Arr::get($data, SourceProduct::PARAM_SOURCE_PRODUCT_TYPE));
            $entity->userCreated()->associate($userId);

            $entity->save();

            $this->spudRepository->create(
                $entity->id,
                Arr::get($data, SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA, [])
            );

            DB::commit();

            return $entity;
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
        return SourceProduct::all();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return SourceProduct::paginate();
    }

    public function update(int $id, array $data): SourceProduct
    {
        DB::beginTransaction();

        try {
            $entity = SourceProduct::findOrFail($id);

            $entity->sourceProductType()->dissociate();
            $entity->sourceProductType()->associate(Arr::get($data, SourceProduct::PARAM_SOURCE_PRODUCT_TYPE));

            $entity->update([
                SourceProduct::PARAM_TITLE => $data[SourceProduct::PARAM_TITLE],
            ]);

            $this->spudRepository->update(
                $entity->id,
                Arr::get($data, SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA, [])
            );

            DB::commit();

            return $entity;
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function delete($id): bool
    {
        $entity = SourceProduct::findOrFail($id);

        return $entity->delete();
    }
}
