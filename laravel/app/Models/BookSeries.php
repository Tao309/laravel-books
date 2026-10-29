<?php

namespace App\Models;

use App\Http\Resources\BookSeries\BookSeriesDataResource;
use App\Http\Resources\BookSeries\BookSeriesResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;

#[UseResource(BookSeriesDataResource::class)]
#[UseResourceCollection(BookSeriesResourceCollection::class)]
class BookSeries extends Model
{
    public const string TABLE_NAME = 'books_series';

    public const string ID = 'id';

    public const string NAME = 'name';

    protected $table = self::TABLE_NAME;

    protected $fillable = [self::ID, self::NAME];
}
