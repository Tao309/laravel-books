<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\Http\QueryFilters\BrandQueryFilter;
use App\Models\Brand;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandRepository implements RepositoryInterface
{
    public function __construct(
        private readonly BrandQueryFilter $queryFilter,
    ) {}

    public function create(DtoInterface $dto): Brand
    {
        return Brand::create($dto->getEntityData());
    }

    public function get(int $id): Brand
    {
        return Brand::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return $this->queryFilter->apply(
            Brand::query(),
            $query->getDTO()
        )->get();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return $this->queryFilter->apply(
            Brand::query(),
            $query->getDTO()
        )->paginate();
    }

    public function update(int $id, DtoInterface $dto): Brand
    {
        $entity = $this->get($id);
        $entity->update($dto->getEntityData());

        return $entity;
    }

    public function delete(int $id): bool
    {
        $entity = $this->get($id);

        return (bool) $entity->delete();
    }
}
