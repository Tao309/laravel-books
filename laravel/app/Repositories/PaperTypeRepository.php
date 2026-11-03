<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\PaperType;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaperTypeRepository implements RepositoryInterface
{
    public function create(array $data): PaperType
    {
        return PaperType::create($data);
    }

    public function get(int $id): PaperType
    {
        return PaperType::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return PaperType::all();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return PaperType::paginate();
    }

    public function update(int $id, array $data): PaperType
    {
        $entity = PaperType::findOrFail($id);
        $entity->update($data);

        return $entity;
    }

    public function delete($id): bool
    {
        $entity = PaperType::findOrFail($id);

        return $entity->delete();
    }
}
