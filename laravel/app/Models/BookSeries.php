<?php

namespace App\Models;

use App\Http\Resources\BookSeries\BookSeriesResource;
use App\Http\Resources\BookSeries\BookSeriesResourceCollection;
use App\Observers\BookSeriesObserver;
use Database\Factories\BookSeriesFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Database\Factories\BookSeriesFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSeries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSeries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSeries query()
 *
 * @mixin \Eloquent
 */
#[UseResource(BookSeriesResource::class)]
#[UseResourceCollection(BookSeriesResourceCollection::class)]
#[ObservedBy([BookSeriesObserver::class])]
class BookSeries extends Model
{
    /** @use HasFactory<BookSeriesFactory> */
    use HasFactory;

    public const string TABLE_NAME = 'book_series';

    public const string PARAM_ID = 'id';

    public const string PARAM_NAME = 'name';

    protected $table = self::TABLE_NAME;

    protected $fillable = [self::PARAM_ID, self::PARAM_NAME];
}
