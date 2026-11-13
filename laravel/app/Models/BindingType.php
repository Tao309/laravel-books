<?php

namespace App\Models;

use App\Http\Resources\BindingTypes\BindingTypeResource;
use App\Http\Resources\BindingTypes\BindingTypesResourceCollection;
use App\Observers\BindingTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $label
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BindingType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BindingType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BindingType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BindingType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BindingType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BindingType whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BindingType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[UseResource(BindingTypeResource::class)]
#[UseResourceCollection(BindingTypesResourceCollection::class)]
#[ObservedBy([BindingTypeObserver::class])]
class BindingType extends Model
{
    public const string TABLE_NAME = 'binding_types';

    public const string PARAM_ID = 'id';

    public const string PARAM_LABEL = 'label';

    protected $table = self::TABLE_NAME;

    protected $fillable = [self::PARAM_LABEL];
}
