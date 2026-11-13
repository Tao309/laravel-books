<?php

namespace App\Models;

use App\Http\Resources\PublishingHouses\PublishingHouseResource;
use App\Http\Resources\PublishingHouses\PublishingHousesResourceCollection;
use App\Observers\PublishingHouseObserver;
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingHouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingHouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingHouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingHouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingHouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingHouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingHouse whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(PublishingHouseResource::class)]
#[UseResourceCollection(PublishingHousesResourceCollection::class)]
#[ObservedBy([PublishingHouseObserver::class])]
class PublishingHouse extends Model
{
    public const string TABLE_NAME = 'publishing_houses';

    public const string PARAM_ID = 'id';

    public const string PARAM_NAME = 'name';

    protected $table = self::TABLE_NAME;

    protected $fillable = [self::PARAM_NAME];
}
