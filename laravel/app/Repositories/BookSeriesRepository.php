<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\BookSeries;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookSeriesRepository implements RepositoryInterface
{
    public function create(array $data): BookSeries
    {
        return BookSeries::create($data);
    }

    public function get(int $id): BookSeries
    {
        return BookSeries::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return BookSeries::all();
    }

    public function getAllPaginate($query): LengthAwarePaginator
    {
        return BookSeries::paginate();
    }

    public function update(int $id, array $data): BookSeries
    {
        $entity = BookSeries::findOrFail($id);
        $entity->update($data);

        return $entity;
    }

    public function delete($id): bool
    {
        return BookSeries::findOrFail($id)->delete();
    }
}
