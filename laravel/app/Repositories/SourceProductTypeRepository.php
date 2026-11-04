<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\SourceProductType;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SourceProductTypeRepository implements RepositoryInterface
{
    public function create(array $data): SourceProductType
    {
        return SourceProductType::create($data);
    }

    public function get(int $id): SourceProductType
    {
        return SourceProductType::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return SourceProductType::all();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return SourceProductType::paginate();
    }

    public function update(int $id, array $data): SourceProductType
    {
        $entity = SourceProductType::findOrFail($id);
        $entity->update($data);

        return $entity;
    }

    public function delete($id): bool
    {
        $entity = SourceProductType::findOrFail($id);

        return $entity->delete();
    }
}
