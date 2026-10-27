<?php

namespace App\Handlers\BindingTypes;

use App\Commands\BindingTypes\DestroyBindingTypeCommand;
use App\Exceptions\ApiException;
use App\Repositories\BindingTypeRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class DestroyBindingTypeHandler
{
    /**
     * @param  BindingTypeRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    /**
     * @throws ApiException
     */
    public function handle(DestroyBindingTypeCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
