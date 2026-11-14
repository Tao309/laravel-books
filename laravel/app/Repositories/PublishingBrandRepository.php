<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\Models\PublishingBrand;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PublishingBrandRepository implements RepositoryInterface
{
    public function create(DtoInterface $dto): PublishingBrand
    {
        return PublishingBrand::create($dto->getEntityData());
    }

    public function get(int $id): PublishingBrand
    {
        return PublishingBrand::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return PublishingBrand::all();
    }

    public function getAllPaginate($query): LengthAwarePaginator
    {
        return PublishingBrand::paginate();
    }

    public function update(int $id, DtoInterface $dto): PublishingBrand
    {
        $entity = PublishingBrand::findOrFail($id);
        $entity->update($dto->getEntityData());

        return $entity;
    }

    public function delete(int $id): bool
    {
        return PublishingBrand::findOrFail($id)->delete();
    }
}
