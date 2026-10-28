<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\Shop;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ShopRepository implements RepositoryInterface
{
    public function create(array $data): Shop
    {
        return Shop::create($data);
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

    public function update(int $id, array $data): Shop
    {
        $entity = Shop::findOrFail($id);
        $entity->update($data);

        return $entity;
    }

    public function delete($id): bool
    {
        $entity = Shop::findOrFail($id);

        return $entity->delete();
    }
}
