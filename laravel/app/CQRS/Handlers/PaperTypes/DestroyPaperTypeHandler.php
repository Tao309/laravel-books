<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\CQRS\Commands\PaperTypes\DestroyPaperTypeCommand;
use App\Exceptions\ApiException;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PaperTypeRepository;

class DestroyPaperTypeHandler extends AbstractPaperTypeHandler
{
    /**
     * @param  PaperTypeRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    /**
     * @throws ApiException
     */
    public function handle(DestroyPaperTypeCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
