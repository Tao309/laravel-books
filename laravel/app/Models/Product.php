<?php

namespace App\Models;

use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\ProductsResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $shop_product_id
 * @property string|null $shop_product_code
 * @property string $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $source_type
 * @property int|null $source_id
 * @property int $shop_id
 * @property int|null $city_id
 * @property int $user_created_id
 * @property-read City|null $city
 * @property-read Collection<int, ProductPrice> $prices
 * @property-read int|null $prices_count
 * @property-read ProductUserData|null $productUserData
 * @property-read Shop $shop
 * @property-read Model|\Eloquent|null $source
 * @property-read Collection<int, ProductStock> $stocks
 * @property-read int|null $stocks_count
 * @property-read User $userCreated
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShopProductCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShopProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUserCreatedId($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(ProductResource::class)]
#[UseResourceCollection(ProductsResourceCollection::class)]
class Product extends Model
{
    public const string TABLE_NAME = 'products';

    public const string PARAM_ID = 'id';

    public const string PARAM_SHOP_PRODUCT_ID = 'shop_product_id';

    public const string PARAM_SHOP_PRODUCT_CODE = 'shop_product_code';

    public const string PARAM_TITLE = 'title';

    public const string PARAM_MIN_PRICE = 'min_price';

    public const string PARAM_LAST_QTY = 'last_qty';

    public const string PARAM_LAST_STOCK = 'last_stock';

    // > morphs
    public const string PARAM_SOURCE = 'source';

    public const string PARAM_SOURCE_TYPE = self::PARAM_SOURCE.'_type';

    public const string PARAM_SOURCE_ID = self::PARAM_SOURCE.'_id';
    // < morphs

    // > relations
    public const string PARAM_BOOK = 'book';

    public const string PARAM_SOURCE_PRODUCT = 'source_product';

    public const string PARAM_SHOP = 'shop';

    public const string PARAM_SHOP_ID = 'shop_id';

    public const string PARAM_CITY = 'city';

    public const string PARAM_CITY_ID = 'city_id';

    public const string PARAM_USER_CREATED = 'user_created';

    public const string PARAM_USER_CREATED_ID = 'user_created_id';

    public const string PARAM_PRODUCT_USER_DATA = 'product_user_data';

    public const string PARAM_PRICES = 'prices';

    public const string PARAM_STOCKS = 'stocks';
    // < relations

    protected $table = self::TABLE_NAME;

    protected $hidden = [
        self::PARAM_SHOP_ID,
        self::PARAM_CITY_ID,
        self::PARAM_USER_CREATED_ID,
    ];

    protected $with = [
        'shop',
        'userCreated',
    ];

    protected $fillable = [
        self::PARAM_SHOP_PRODUCT_ID,
        self::PARAM_SHOP_PRODUCT_CODE,
        self::PARAM_TITLE,
        self::PARAM_SHOP_ID,
        self::PARAM_CITY_ID,
        self::PARAM_USER_CREATED_ID,
        'source_type',
        'source_id',
    ];

    protected function casts(): array
    {
        return [
            'minPrice' => 'integer',
            'lastQty' => 'integer',
        ];
    }

    // Book, SourceProduct
    public function source(): MorphTo
    {
        return $this->morphTo('source', 'source_type', 'source_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, self::PARAM_SHOP_ID);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, self::PARAM_CITY_ID);
    }

    public function userCreated(): BelongsTo
    {
        return $this->belongsTo(User::class, self::PARAM_USER_CREATED_ID);
    }

    public function productUserData(): HasOne
    {
        return $this->hasOne(ProductUserData::class, ProductUserData::PARAM_PRODUCT_ID, self::PARAM_ID)->where([
            ProductUserData::PARAM_USER_ID => Auth::id() ?? null,
        ]);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class, ProductPrice::PARAM_PRODUCT_ID, self::PARAM_ID)->where([
            ProductPrice::PARAM_USER_ID => Auth::id() ?? null,
        ])->orderBy(ProductPrice::PARAM_DATE);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class, ProductStock::PARAM_PRODUCT_ID, self::PARAM_ID)->where([
            ProductStock::PARAM_USER_ID => Auth::id() ?? null,
        ])->orderBy(ProductStock::PARAM_DATE);
    }

    public function minPrice(): ?int
    {
        return $this->prices()->count() ? $this->prices()->min(ProductPrice::PARAM_PRICE) : null;
    }

    public function lastQty(): ?int
    {
        return $this->stocks()->count() ? $this->stocks->last()->qty : null;
    }

    public function lastStock(): ?ProductStock
    {
        return $this->stocks()->count() ? $this->stocks->last() : null;
    }
}
