<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface UserDataRepositoryInterface
{
    public function get(int $bookId): Model;

    public function create(int $bookId, array $data): Model;

    public function update(int $bookId, array $data): int;

    public function delete(int $bookId): void;
}
