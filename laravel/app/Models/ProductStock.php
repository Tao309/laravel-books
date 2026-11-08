<?php

namespace App\Models;

use App\Http\Resources\ProductStocks\ProductStockResource;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $product_id
 * @property int $user_id
 * @property int $qty
 * @property Carbon $date
 * @property array<array-key, mixed>|null $log
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(ProductStockResource::class)]
class ProductStock extends Model
{
    public const string TABLE_NAME = 'product_stocks';

    public const string PARAM_PRODUCT_ID = 'product_id';

    public const string PARAM_USER_ID = 'user_id';

    public const string PARAM_QTY = 'qty';

    public const string PARAM_DATE = 'date';

    public const string PARAM_LOG = 'log';

    protected $table = self::TABLE_NAME;

    protected $primaryKey = [self::PARAM_PRODUCT_ID, self::PARAM_USER_ID];

    public $incrementing = false;

    public $timestamps = false;

    protected $hidden = [
        self::PARAM_PRODUCT_ID,
        self::PARAM_USER_ID,
    ];

    protected $with = [];

    protected $fillable = [
        self::PARAM_PRODUCT_ID,
        self::PARAM_USER_ID,
        self::PARAM_QTY,
        self::PARAM_DATE,
        self::PARAM_LOG,
    ];

    protected function casts(): array
    {
        return [
            self::PARAM_DATE => 'datetime',
            self::PARAM_LOG => 'array',
        ];
    }

    //    public function product(): BelongsTo
    //    {
    //        return $this->belongsTo(Product::class, self::PARAM_PRODUCT_ID);
    //    }
}
