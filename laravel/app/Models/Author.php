<?php

namespace App\Models;

use App\Http\Resources\Authors\AuthorDataResource;
use App\Http\Resources\Authors\AuthorsResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Book> $books
 * @property-read int|null $books_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(AuthorDataResource::class)]
#[UseResourceCollection(AuthorsResourceCollection::class)]
class Author extends Model
{
    public const string TABLE_NAME = 'authors';

    public const string PARAM_ID = 'id';

    public const string PARAM_FIRST_NAME = 'first_name';

    public const string PARAM_LAST_NAME = 'last_name';

    public const string PIVOT_AUTHOR_BOOKS_TABLE = 'author_books';

    public const string PIVOT_AUTHOR_BOOKS_AUTHOR_ID = 'author_id';

    public const string PIVOT_AUTHOR_BOOKS_BOOK_ID = 'book_id';

    protected $table = self::TABLE_NAME;

    protected $fillable = [self::PARAM_FIRST_NAME, self::PARAM_LAST_NAME];

    protected $perPage = 100;

    protected $hidden = ['pivot'];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(
            Book::class,
            self::PIVOT_AUTHOR_BOOKS_TABLE,
            self::PIVOT_AUTHOR_BOOKS_AUTHOR_ID,
            self::PIVOT_AUTHOR_BOOKS_BOOK_ID,
        );
    }
}
