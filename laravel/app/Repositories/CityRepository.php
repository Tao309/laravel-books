<?php

namespace App\Repositories;

use App\Models\City;
use App\Queries\GetCollectionQueryInterface;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CityRepository implements RepositoryInterface
{
    public function create(array $data): City
    {
        return City::create($data);
    }

    public function get(int $id): City
    {
        return City::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return City::all();
    }

    public function getAllPaginate($query): LengthAwarePaginator
    {
        return City::paginate();
    }

    public function update(int $id, array $data): City
    {
        $entity = City::findOrFail($id);
        $entity->update($data);

        return $entity;
    }

    public function delete($id): bool
    {
        $entity = City::findOrFail($id);

        return $entity->delete();
    }
}
