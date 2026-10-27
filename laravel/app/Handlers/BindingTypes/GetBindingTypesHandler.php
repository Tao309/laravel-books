<?php

namespace App\Handlers\BindingTypes;

use App\Queries\BindingTypes\GetBindingTypesQuery;
use App\Repositories\BindingTypeRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetBindingTypesHandler
{
    /**
     * @param  BindingTypeRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    public function handle(GetBindingTypesQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
