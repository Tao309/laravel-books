<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\SourceProduct;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SourceProductRepository implements RepositoryInterface
{
    public function create(array $data): SourceProduct
    {
        DB::beginTransaction();
        try {
            $entity = SourceProduct::newModelInstance($data);

            // @todo make more protected
            if (! empty($data[SourceProduct::PARAM_ID])) {
                $entity->mergeFillable([SourceProduct::PARAM_ID]);
            }

            $entity->sourceProductType()->associate(Arr::get($data, SourceProduct::PARAM_SOURCE_PRODUCT_TYPE));
            // @todo заменить на реального текущего пользователя
            $entity->userCreated()->associate(1);

            $entity->save();

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

            $entity->update($data);

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
