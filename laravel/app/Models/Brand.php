<?php

namespace App\Models;

use App\Http\Resources\Brands\BrandResource;
use App\Http\Resources\Brands\BrandsResourceCollection;
use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $code
 * @property string $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Database\Factories\BrandFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(BrandResource::class)]
#[UseResourceCollection(BrandsResourceCollection::class)]
class Brand extends Model
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory;

    public const string TABLE_NAME = 'brands';

    public const string PARAM_ID = 'id';

    public const string PARAM_CODE = 'code';

    public const string PARAM_TITLE = 'title';

    protected $perPage = 100;

    protected $table = self::TABLE_NAME;

    protected $fillable = [
        self::PARAM_CODE,
        self::PARAM_TITLE,
    ];

    protected function code(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => $this->normalizeCode($value),
        );
    }

    protected static function booted(): void
    {
        static::saving(function (Brand $brand) {
            if (empty($brand->code)) {
                $brand->code = $brand->title;
            }
        });
    }

    private function normalizeCode(string $value): string
    {
        $value = preg_replace('/([a-z0-9])([A-Z])/', '$1-$2', $value);

        return Str::slug($value, '-');
    }
}
