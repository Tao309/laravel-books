<?php

namespace App\Http\Resources\BookSeries;

use App\Http\Resources\AbstractResourceCollection;

class BookSeriesResourceCollection extends AbstractResourceCollection
{
    public $collects = BookSeriesResource::class;
}
