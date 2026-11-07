<?php

namespace App\Models;

use App\Http\Resources\ProductUserDatas\ProductUserDataResource;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property bool $available
 * @property Carbon|null $not_available_date_from
 * @property Carbon|null $available_date_from
 * @property int|null $listen_price_value
 * @property int|null $listen_qty_value
 * @property Carbon|null $release_date
 * @property bool $is_archive
 * @property string|null $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $product_id
 * @property int $user_id
 * @property-read Product $product
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereAvailableDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereIsArchive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereListenPriceValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereListenQtyValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereNotAvailableDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUserData whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(ProductUserDataResource::class)]
class ProductUserData extends Model
{
    public const string TABLE_NAME = 'product_user_data';

    public const string PARAM_PRODUCT_ID = 'product_id';

    public const string PARAM_USER_ID = 'user_id';

    public const string PARAM_AVAILABLE = 'available';

    public const string PARAM_NOT_AVAILABLE_DATE_FROM = 'not_available_date_from';

    public const string PARAM_AVAILABLE_DATE_FROM = 'available_date_from';

    public const string PARAM_LISTEN_PRICE_VALUE = 'listen_price_value';

    public const string PARAM_LISTEN_QTY_VALUE = 'listen_qty_value';

    public const string PARAM_RELEASE_DATE = 'release_date';

    public const string PARAM_IS_ARCHIVE = 'is_archive';

    public const string PARAM_COMMENT = 'comment';

    protected $table = self::TABLE_NAME;

    protected $primaryKey = [self::PARAM_PRODUCT_ID, self::PARAM_USER_ID];

    public $incrementing = false;

    protected $hidden = [
        self::PARAM_PRODUCT_ID,
        self::PARAM_USER_ID,
    ];

    protected $with = [];

    protected $fillable = [
        self::PARAM_PRODUCT_ID,
        self::PARAM_USER_ID,
        self::PARAM_AVAILABLE,
        self::PARAM_NOT_AVAILABLE_DATE_FROM,
        self::PARAM_AVAILABLE_DATE_FROM,
        self::PARAM_LISTEN_PRICE_VALUE,
        self::PARAM_LISTEN_QTY_VALUE,
        self::PARAM_RELEASE_DATE,
        self::PARAM_IS_ARCHIVE,
        self::PARAM_COMMENT,
    ];

    protected function casts(): array
    {
        return [
            self::PARAM_NOT_AVAILABLE_DATE_FROM => 'datetime',
            self::PARAM_AVAILABLE_DATE_FROM => 'datetime',
            self::PARAM_RELEASE_DATE => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, self::PARAM_PRODUCT_ID);
    }
}
