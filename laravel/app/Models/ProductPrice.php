<?php

namespace App\Models;

use App\Http\Resources\ProductPrices\ProductPriceResource;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $product_id
 * @property int $user_id
 * @property int $price
 * @property Carbon $date
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(ProductPriceResource::class)]
class ProductPrice extends Model
{
    public const string TABLE_NAME = 'product_prices';

    public const string PARAM_PRODUCT_ID = 'product_id';

    public const string PARAM_USER_ID = 'user_id';

    public const string PARAM_PRICE = 'price';

    public const string PARAM_DATE = 'date';

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
        self::PARAM_PRICE,
        self::PARAM_DATE,
    ];

    protected function casts(): array
    {
        return [
            self::PARAM_DATE => 'datetime',
        ];
    }

    //    public function product(): BelongsTo
    //    {
    //        return $this->belongsTo(Product::class, self::PARAM_PRODUCT_ID);
    //    }
}
