<?php

namespace App\Models;

use App\Http\Resources\SourceProductTypes\SourceProductTypeDataResource;
use App\Http\Resources\SourceProductTypes\SourceProductTypesDataResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(SourceProductTypeDataResource::class)]
#[UseResourceCollection(SourceProductTypesDataResourceCollection::class)]
class SourceProductType extends Model
{
    public const string TABLE_NAME = 'source_product_types';

    public const string PARAM_ID = 'id';

    public const string PARAM_CODE = 'code';

    public const string PARAM_NAME = 'name';

    protected $table = self::TABLE_NAME;

    protected $fillable = [self::PARAM_CODE, self::PARAM_NAME];
}
