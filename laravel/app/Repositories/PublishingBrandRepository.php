<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\PublishingBrand;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PublishingBrandRepository implements RepositoryInterface
{
    public function create(array $data): PublishingBrand
    {
        return PublishingBrand::create($data);
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

    public function update(int $id, array $data): PublishingBrand
    {
        $entity = PublishingBrand::findOrFail($id);
        $entity->update($data);

        return $entity;
    }

    public function delete($id): bool
    {
        return PublishingBrand::findOrFail($id)->delete();
    }
}
