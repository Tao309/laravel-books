<?php

namespace App\Models;

use App\Http\Resources\BookSeries\BookSeriesResource;
use App\Http\Resources\BookSeries\BookSeriesResourceCollection;
use App\Observers\BookSeriesObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSeries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSeries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSeries query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSeries whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSeries whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSeries whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSeries whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(BookSeriesResource::class)]
#[UseResourceCollection(BookSeriesResourceCollection::class)]
#[ObservedBy([BookSeriesObserver::class])]
class BookSeries extends Model
{
    public const string TABLE_NAME = 'books_series';

    public const string PARAM_ID = 'id';

    public const string PARAM_NAME = 'name';

    protected $table = self::TABLE_NAME;

    protected $fillable = [self::PARAM_ID, self::PARAM_NAME];
}
