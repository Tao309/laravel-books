<?php

namespace App\Models;

use App\Http\Resources\SourceProducts\SourceProductDataResource;
use App\Http\Resources\SourceProducts\SourceProductsDataResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $source_product_type_id
 * @property int $user_created_id
 * @property-read Collection<int, Product> $products
 * @property-read int|null $products_count
 * @property-read SourceProductType $sourceProductType
 * @property-read SourceProductUserData|null $sourceProductUserData
 * @property-read User $userCreated
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProduct whereSourceProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProduct whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProduct whereUserCreatedId($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(SourceProductDataResource::class)]
#[UseResourceCollection(SourceProductsDataResourceCollection::class)]
class SourceProduct extends Model
{
    public const string TABLE_NAME = 'source_products';

    public const string PARAM_ID = 'id';

    public const string PARAM_TITLE = 'title';

    public const string PARAM_SOURCE_PRODUCT_TYPE = 'source_product_type';

    public const string PARAM_USER_CREATED = 'user_created';

    public const string PARAM_SOURCE_PRODUCT_TYPE_ID = 'source_product_type_id';

    public const string PARAM_USER_CREATED_ID = 'user_created_id';

    public const string PARAM_SOURCE_PRODUCT_USER_DATA = 'source_product_user_data';

    protected $table = self::TABLE_NAME;

    protected $hidden = [
        SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID,
        SourceProduct::PARAM_USER_CREATED_ID,
    ];

    protected $with = [
        'sourceProductType',
        'userCreated',
    ];

    protected $fillable = [
        self::PARAM_TITLE,
        self::PARAM_SOURCE_PRODUCT_TYPE_ID,
        self::PARAM_USER_CREATED_ID,
    ];

    public function sourceProductType(): BelongsTo
    {
        return $this->belongsTo(SourceProductType::class, self::PARAM_SOURCE_PRODUCT_TYPE_ID);
    }

    public function userCreated(): BelongsTo
    {
        return $this->belongsTo(User::class, self::PARAM_USER_CREATED_ID);
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, Product::PARAM_SOURCE);
    }

    public function sourceProductUserData(): HasOne
    {
        return $this->hasOne(SourceProductUserData::class, SourceProductUserData::PARAM_SOURCE_PRODUCT_ID, self::PARAM_ID)
            ->where([
                SourceProductUserData::PARAM_USER_ID => Auth::id() ?? null,
            ]);
    }
}
