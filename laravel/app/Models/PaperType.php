<?php

namespace App\Models;

use App\Http\Resources\PaperTypes\PaperTypeDataResource;
use App\Http\Resources\PaperTypes\PaperTypesResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $label
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperType whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(PaperTypeDataResource::class)]
#[UseResourceCollection(PaperTypesResourceCollection::class)]
class PaperType extends Model
{
    public const string TABLE_NAME = 'paper_types';

    public const string PARAM_ID = 'id';

    public const string PARAM_LABEL = 'label';

    public const string PARAM_DESCRIPTION = 'description';

    protected $table = self::TABLE_NAME;

    protected $fillable = [
        self::PARAM_LABEL,
        self::PARAM_DESCRIPTION,
    ];
}
