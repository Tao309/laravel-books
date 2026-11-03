<?php

namespace App\Models;

use App\Http\Resources\PublishingBrand\PublishingBrandDataResource;
use App\Http\Resources\PublishingBrand\PublishingBrandResourceCollection;
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingBrand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingBrand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingBrand query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingBrand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingBrand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingBrand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublishingBrand whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(PublishingBrandDataResource::class)]
#[UseResourceCollection(PublishingBrandResourceCollection::class)]
class PublishingBrand extends Model
{
    public const string TABLE_NAME = 'publishing_brands';

    public const string ID = 'id';

    public const string NAME = 'name';

    protected $table = self::TABLE_NAME;

    protected $fillable = [self::NAME];
}
