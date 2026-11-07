<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\Models\PublishingHouse;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PublishingHouseRepository implements RepositoryInterface
{
    public function create(DtoInterface $dto): PublishingHouse
    {
        return PublishingHouse::create($dto->getEntityData());
    }

    public function get(int $id): PublishingHouse
    {
        return PublishingHouse::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return PublishingHouse::all();
    }

    public function getAllPaginate($query): LengthAwarePaginator
    {
        return PublishingHouse::paginate();
    }

    public function update(int $id, DtoInterface $dto): PublishingHouse
    {
        $entity = PublishingHouse::findOrFail($id);
        $entity->update($dto->getEntityData());

        return $entity;
    }

    public function delete($id): bool
    {
        return PublishingHouse::findOrFail($id)->delete();
    }
}
