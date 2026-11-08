<?php

namespace App\Models;

use App\Http\Resources\SourceProductUserDatas\SourceProductUserDataResource;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $source_product_id
 * @property int $user_id
 * @property int|null $listen_price_value
 * @property string|null $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SourceProduct $book
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductUserData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductUserData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductUserData query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductUserData whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductUserData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductUserData whereListenPriceValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductUserData whereSourceProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductUserData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SourceProductUserData whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(SourceProductUserDataResource::class)]
class SourceProductUserData extends Model
{
    public const string TABLE_NAME = 'source_product_user_data';

    public const string PARAM_SOURCE_PRODUCT_ID = 'source_product_id';

    public const string PARAM_USER_ID = 'user_id';

    public const string PARAM_LISTEN_PRICE_VALUE = 'listen_price_value';

    public const string PARAM_COMMENT = 'comment';

    protected $table = self::TABLE_NAME;

    protected $primaryKey = [self::PARAM_SOURCE_PRODUCT_ID, self::PARAM_USER_ID];

    public $incrementing = false;

    protected $hidden = [
        self::PARAM_SOURCE_PRODUCT_ID,
        self::PARAM_USER_ID,
    ];

    protected $with = [];

    protected $fillable = [
        self::PARAM_SOURCE_PRODUCT_ID,
        self::PARAM_USER_ID,
        self::PARAM_LISTEN_PRICE_VALUE,
        self::PARAM_COMMENT,
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(SourceProduct::class, self::PARAM_SOURCE_PRODUCT_ID);
    }
}
