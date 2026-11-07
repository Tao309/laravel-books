<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\Models\Book;
use App\Models\Product;
use App\Models\SourceProduct;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductRepository implements RepositoryInterface
{
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

            DB::commit();

            return $entity;
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function get(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return Product::all();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return Product::paginate();
    }

    public function update(int $id, DtoInterface $dto): Product
    {
        $relationData = $dto->getRelationsData();

        DB::beginTransaction();
        try {
            $entity = Product::findOrFail($id);

            $this->setSourceToModel($entity, $relationData);

            if (Arr::has($relationData, Product::PARAM_SHOP)) {
                $entity->shop()->dissociate();
                $entity->shop()->associate(Arr::get($relationData, Product::PARAM_SHOP));
            }

            if (Arr::has($relationData, Product::PARAM_CITY)) {
                $entity->city()->dissociate();
                $entity->city()->associate(Arr::get($relationData, Product::PARAM_CITY));
            }

            $entity->update($dto->getEntityData());

            DB::commit();

            return $entity;
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function delete($id): bool
    {
        $entity = Product::findOrFail($id);

        return $entity->delete();
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
