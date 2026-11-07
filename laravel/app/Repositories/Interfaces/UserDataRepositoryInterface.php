<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface UserDataRepositoryInterface
{
    public function get(int $relatedEntityId): Model;

    public function create(int $relatedEntityId, array $data): Model;

    public function update(int $relatedEntityId, array $data): int;

    public function delete(int $relatedEntityId): void;
}
