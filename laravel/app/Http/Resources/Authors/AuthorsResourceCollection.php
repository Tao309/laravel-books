<?php

namespace App\Http\Resources\Authors;

use App\Http\Resources\AbstractResourceCollection;

class AuthorsResourceCollection extends AbstractResourceCollection
{
    public $collects = AuthorResource::class;
}
