<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\Models\BindingType;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BindingTypeRepository implements RepositoryInterface
{
    public function create(DtoInterface $dto): BindingType
    {
        return BindingType::create($dto->getEntityData());
    }

    public function get(int $id): BindingType
    {
        return BindingType::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return BindingType::all();
    }

    public function getAllPaginate($query): LengthAwarePaginator
    {
        return BindingType::paginate();
    }

    public function update(int $id, DtoInterface $dto): BindingType
    {
        $entity = BindingType::findOrFail($id);
        $entity->update($dto->getEntityData());

        return $entity;
    }

    public function delete(int $id): bool
    {
        $entity = BindingType::findOrFail($id);

        return $entity->delete();
    }
}
