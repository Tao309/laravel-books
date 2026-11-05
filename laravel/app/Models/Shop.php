<?php

namespace App\Models;

use App\Http\Resources\Shops\ShopDataResource;
use App\Http\Resources\Shops\ShopResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $type
 * @property string $domain
 * @property string|null $url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereUrl($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(ShopDataResource::class)]
#[UseResourceCollection(ShopResourceCollection::class)]
class Shop extends Model
{
    public const string TABLE_NAME = 'shops';

    public const string PARAM_ID = 'id';

    public const string PARAM_TYPE = 'type';

    public const string PARAM_DOMAIN = 'domain';

    public const string PARAM_URL = 'url';

    protected $table = self::TABLE_NAME;
}
