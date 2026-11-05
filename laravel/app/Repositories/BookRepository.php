<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\Book;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BookRepository implements RepositoryInterface
{
    public function create(array $data): Book
    {
        DB::beginTransaction();

        try {
            $book = Book::newModelInstance($data);

            $book->bindingType()->associate(Arr::get($data, Book::PARAM_BINDING_TYPE));

            if (Arr::has($data, Book::PARAM_PAPER_TYPE)) {
                $book->paperType()->associate(Arr::get($data, Book::PARAM_PAPER_TYPE));
            }

            if (Arr::has($data, Book::PARAM_PUBLISHING_HOUSE)) {
                $book->publishingHouse()->associate(Arr::get($data, Book::PARAM_PUBLISHING_HOUSE));
            }

            if (Arr::has($data, Book::PARAM_PUBLISHING_BRAND)) {
                $book->publishingBrand()->associate(Arr::get($data, Book::PARAM_PUBLISHING_BRAND));
            }

            if (Arr::has($data, Book::PARAM_BOOK_SERIES)) {
                $book->bookSeries()->associate(Arr::get($data, Book::PARAM_BOOK_SERIES));
            }

            // @todo заменить на реального текущего пользователя
            $book->userCreated()->associate(1);

            $book->save();

            $book->authors()->attach(Arr::get($data, Book::PARAM_AUTHORS));

            DB::commit();

            return $book;
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function update(int $id, array $data): Book
    {
        DB::beginTransaction();
        try {
            $book = Book::findOrFail($id);

            $book->bindingType()->dissociate();
            $book->bindingType()->associate(Arr::get($data, Book::PARAM_BINDING_TYPE));

            $book->paperType()->dissociate();
            $book->paperType()->associate(Arr::get($data, Book::PARAM_PAPER_TYPE));

            $book->publishingHouse()->dissociate();
            $book->publishingHouse()->associate(Arr::get($data, Book::PARAM_PUBLISHING_HOUSE));

            $book->publishingBrand()->dissociate();
            $book->publishingBrand()->associate(Arr::get($data, Book::PARAM_PUBLISHING_BRAND));

            $book->bookSeries()->dissociate();
            $book->bookSeries()->associate(Arr::get($data, Book::PARAM_BOOK_SERIES));

            $book->authors()->detach();
            $book->authors()->attach(Arr::get($data, Book::PARAM_AUTHORS));

            $book->update($data);

            DB::commit();

            return Book::findOrFail($book->id);
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function get(int $id): Book
    {
        return Book::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return Book::all();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return Book::paginate();
    }

    public function delete($id): bool
    {
        $entity = Book::findOrFail($id);

        return $entity->delete();
    }
}
