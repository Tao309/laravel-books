<?php

namespace App\Repositories\Interfaces;

use App\DTOs\UserDataDtoInterface;
use Illuminate\Database\Eloquent\Model;

interface UserDataRepositoryInterface
{
    public function get(int $relEntityId): Model;

    public function create(int $relEntityId, UserDataDtoInterface $dto): Model;

    public function update(int $relEntityId, UserDataDtoInterface $dto): int;

    public function delete(int $relEntityId): void;
}
