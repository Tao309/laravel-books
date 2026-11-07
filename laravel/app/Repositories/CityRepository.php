<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\Models\City;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CityRepository implements RepositoryInterface
{
    public function create(DtoInterface $dto): City
    {
        return City::create($dto->getEntityData());
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

    public function update(int $id, DtoInterface $dto): City
    {
        $entity = City::findOrFail($id);
        $entity->update($dto->getEntityData());

        return $entity;
    }

    public function delete($id): bool
    {
        $entity = City::findOrFail($id);

        return $entity->delete();
    }
}
