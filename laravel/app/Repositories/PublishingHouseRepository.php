<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\PublishingHouse;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PublishingHouseRepository implements RepositoryInterface
{
    public function create(array $data): PublishingHouse
    {
        return PublishingHouse::create($data);
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

    public function update(int $id, array $data): PublishingHouse
    {
        $entity = PublishingHouse::findOrFail($id);
        $entity->update($data);

        return $entity;
    }

    public function delete($id): bool
    {
        return PublishingHouse::findOrFail($id)->delete();
    }
}
