<?php

namespace App\Models;

use App\Http\Resources\Books\BookDataResource;
use App\Http\Resources\Books\BooksResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string|null $original_title
 * @property string|null $isbn
 * @property int|null $pages
 * @property int|null $circulation
 * @property string|null $size
 * @property int $publish_year
 * @property string|null $livelib_id
 * @property string|null $goodreads_id
 * @property string|null $fantlab_id
 * @property float|null $livelib_rating
 * @property float|null $goodreads_rating
 * @property int $on_sale_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $binding_type_id
 * @property int|null $paper_type_id
 * @property int|null $publishing_house_id
 * @property int|null $publishing_brand_id
 * @property int|null $book_series_id
 * @property int $user_created_id
 * @property-read Collection<int, Author> $authors
 * @property-read int|null $authors_count
 * @property-read BindingType $bindingType
 * @property-read BookSeries|null $bookSeries
 * @property-read PaperType|null $paperType
 * @property-read Collection<int, Product> $products
 * @property-read int|null $products_count
 * @property-read PublishingBrand|null $publishingBrand
 * @property-read PublishingHouse|null $publishingHouse
 * @property-read User $userCreated
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereBindingTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereBookSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereCirculation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereFantlabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereGoodreadsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereGoodreadsRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereLivelibId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereLivelibRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereOnSaleCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereOriginalTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book wherePages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book wherePaperTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book wherePublishYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book wherePublishingBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book wherePublishingHouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereUserCreatedId($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(BookDataResource::class)]
#[UseResourceCollection(BooksResourceCollection::class)]
class Book extends Model
{
    public const string TABLE_NAME = 'books';

    public const string PARAM_ID = 'id';

    public const string PARAM_TITLE = 'title';

    public const string PARAM_ORIGINAL_TITLE = 'original_title';

    public const string PARAM_ISBN = 'isbn';

    public const string PARAM_PAGES = 'pages';

    public const string PARAM_CIRCULATION = 'circulation';

    public const string PARAM_SIZE = 'size';

    public const string PARAM_PUBLISH_YEAR = 'publish_year';

    public const string PARAM_LIVELIB_ID = 'livelib_id';

    public const string PARAM_GOODREADS_ID = 'goodreads_id';

    public const string PARAM_FANTLAB_ID = 'fantlab_id';

    public const string PARAM_LIVELIB_RATING = 'livelib_rating';

    public const string PARAM_GOODREADS_RATING = 'goodreads_rating';

    public const string PARAM_ON_SALE_COUNT = 'on_sale_count';

    public const string PARAM_AUTHORS = 'authors';

    public const string PARAM_BINDING_TYPE = 'binding_type';

    public const string PARAM_PAPER_TYPE = 'paper_type';

    public const string PARAM_PUBLISHING_HOUSE = 'publishing_house';

    public const string PARAM_PUBLISHING_BRAND = 'publishing_brand';

    public const string PARAM_BOOK_SERIES = 'book_series';

    public const string PARAM_USER_CREATED = 'user_created';

    public const string PARAM_BINDING_TYPE_ID = 'binding_type_id';

    public const string PARAM_PAPER_TYPE_ID = 'paper_type_id';

    public const string PARAM_PUBLISHING_HOUSE_ID = 'publishing_house_id';

    public const string PARAM_PUBLISHING_BRAND_ID = 'publishing_brand_id';

    public const string PARAM_BOOK_SERIES_ID = 'book_series_id';

    public const string PARAM_USER_CREATED_ID = 'user_created_id';

    protected $table = self::TABLE_NAME;

    protected $perPage = 100;

    protected $hidden = [
        'binding_type_id',
        'publishing_house_id',
        'publishing_brand_id',
        'book_series_id',
        'user_created_id',
    ];

    protected $with = [
        'authors',
        'bindingType',
        'paperType',
        'publishingHouse',
        'publishingBrand',
        'bookSeries',
        'userCreated',
    ];

    protected $fillable = [
        self::PARAM_ID,
        self::PARAM_TITLE,
        self::PARAM_ORIGINAL_TITLE,
        self::PARAM_ISBN,
        self::PARAM_PAGES,
        self::PARAM_CIRCULATION,
        self::PARAM_SIZE,
        self::PARAM_PUBLISH_YEAR,
        self::PARAM_LIVELIB_ID,
        self::PARAM_GOODREADS_ID,
        self::PARAM_FANTLAB_ID,
        self::PARAM_LIVELIB_RATING,
        self::PARAM_GOODREADS_RATING,
        self::PARAM_ON_SALE_COUNT,
        self::PARAM_BINDING_TYPE_ID,
        self::PARAM_PAPER_TYPE_ID,
        self::PARAM_PUBLISHING_HOUSE_ID,
        self::PARAM_PUBLISHING_BRAND_ID,
        self::PARAM_BOOK_SERIES_ID,
        self::PARAM_USER_CREATED_ID,
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(
            Author::class,
            Author::PIVOT_AUTHOR_BOOKS_TABLE,
            Author::PIVOT_AUTHOR_BOOKS_BOOK_ID,
            Author::PIVOT_AUTHOR_BOOKS_AUTHOR_ID,
        )->withTimestamps();
    }

    public function bindingType(): BelongsTo
    {
        return $this->belongsTo(BindingType::class, 'binding_type_id');
    }

    public function paperType(): BelongsTo
    {
        return $this->belongsTo(PaperType::class, 'paper_type_id');
    }

    public function publishingHouse(): BelongsTo
    {
        return $this->belongsTo(PublishingHouse::class, 'publishing_house_id');
    }

    public function publishingBrand(): BelongsTo
    {
        return $this->belongsTo(PublishingBrand::class, 'publishing_brand_id');
    }

    public function bookSeries(): BelongsTo
    {
        return $this->belongsTo(BookSeries::class, 'book_series_id');
    }

    public function userCreated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_created_id');
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, Product::PARAM_SOURCE);
    }
}
