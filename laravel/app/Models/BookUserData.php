<?php

namespace App\Models;

use App\Http\Resources\BookUserDatas\BookUserDataResource;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string|null $release_date
 * @property int|null $listen_price_value
 * @property string|null $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $book_id
 * @property int $user_id
 * @property-read Book $book
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookUserData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookUserData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookUserData query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookUserData whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookUserData whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookUserData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookUserData whereListenPriceValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookUserData whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookUserData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookUserData whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(BookUserDataResource::class)]
class BookUserData extends Model
{
    public const string TABLE_NAME = 'book_user_data';

    public const string PARAM_BOOK_ID = 'book_id';

    public const string PARAM_USER_ID = 'user_id';

    public const string PARAM_RELEASE_DATE = 'release_date';

    public const string PARAM_LISTEN_PRICE_VALUE = 'listen_price_value';

    public const string PARAM_COMMENT = 'comment';

    protected $table = self::TABLE_NAME;

    protected $primaryKey = [self::PARAM_BOOK_ID, self::PARAM_USER_ID];

    public $incrementing = false;

    protected $hidden = [
        self::PARAM_BOOK_ID,
        self::PARAM_USER_ID,
    ];

    protected $with = [];

    protected $fillable = [
        self::PARAM_BOOK_ID,
        self::PARAM_USER_ID,
        self::PARAM_RELEASE_DATE,
        self::PARAM_LISTEN_PRICE_VALUE,
        self::PARAM_COMMENT,
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, self::PARAM_BOOK_ID);
    }
}
