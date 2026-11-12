<?php

namespace App\Http\Resources\Books;

use App\Http\Resources\AbstractResourceCollection;

class BooksResourceCollection extends AbstractResourceCollection
{
    public $collects = BookResource::class;
}
