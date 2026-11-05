<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\Book;
use App\Models\Product;
use App\Models\SourceProduct;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductRepository implements RepositoryInterface
{
    public function create(array $data): Product
    {
        DB::beginTransaction();
        try {
            $entity = Product::newModelInstance($data);

            $this->setSourceToModel($entity, $data);

            $entity->shop()->associate(Arr::get($data, Product::PARAM_SHOP));

            if (Arr::has($data, Product::PARAM_CITY)) {
                $entity->city()->associate(Arr::get($data, Product::PARAM_CITY));
            }

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

    public function update(int $id, array $data): Product
    {
        DB::beginTransaction();
        try {
            $entity = Product::findOrFail($id);

            $this->setSourceToModel($entity, $data);

            if (Arr::has($data, Product::PARAM_SHOP)) {
                $entity->shop()->dissociate();
                $entity->shop()->associate(Arr::get($data, Product::PARAM_SHOP));
            }

            if (Arr::has($data, Product::PARAM_CITY)) {
                $entity->city()->dissociate();
                $entity->city()->associate(Arr::get($data, Product::PARAM_CITY));
            }

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
