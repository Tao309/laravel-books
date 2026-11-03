<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\CQRS\Commands\PaperTypes\GetByIdPaperTypeCommand;
use App\Models\PaperType;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PaperTypeRepository;

class GetByIdPaperTypeHandler extends AbstractPaperTypeHandler
{
    /**
     * @param  PaperTypeRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    public function handle(GetByIdPaperTypeCommand $query): PaperType
    {
        return $this->repository->get($query->id);
    }
}
