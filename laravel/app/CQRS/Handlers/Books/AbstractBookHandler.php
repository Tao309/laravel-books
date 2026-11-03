<?php

namespace App\CQRS\Handlers\Books;

use App\Repositories\BookRepository;
use App\Repositories\Interfaces\RepositoryInterface;

abstract class AbstractBookHandler
{
    /**
     * @param  BookRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
