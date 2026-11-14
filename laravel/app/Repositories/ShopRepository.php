<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\Models\Shop;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ShopRepository implements RepositoryInterface
{
    public function create(DtoInterface $dto): Shop
    {
        return Shop::create($dto->getEntityData());
    }

    public function get(int $id): Shop
    {
        return Shop::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return Shop::all();
    }

    public function getAllPaginate($query): LengthAwarePaginator
    {
        return Shop::paginate();
    }

    public function update(int $id, DtoInterface $dto): Shop
    {
        $entity = Shop::findOrFail($id);
        $entity->update($dto->getEntityData());

        return $entity;
    }

    public function delete(int $id): bool
    {
        $entity = Shop::findOrFail($id);

        return $entity->delete();
    }
}
