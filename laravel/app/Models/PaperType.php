<?php

namespace App\Models;

use App\Http\Resources\PaperTypes\PaperTypeResource;
use App\Http\Resources\PaperTypes\PaperTypesResourceCollection;
use App\Observers\PaperTypeObserver;
use Database\Factories\PaperTypeFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $label
 * @property string|null $description
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
#[UseResource(PaperTypeResource::class)]
#[UseResourceCollection(PaperTypesResourceCollection::class)]
#[ObservedBy([PaperTypeObserver::class])]
class PaperType extends Model
{
    /** @use HasFactory<PaperTypeFactory> */
    use HasFactory;

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
