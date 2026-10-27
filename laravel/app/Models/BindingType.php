<?php

namespace App\Models;

use App\Http\Resources\BindingTypes\BindingTypeCollection;
use App\Http\Resources\BindingTypes\BindingTypeDataResource;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;

#[UseResource(BindingTypeDataResource::class)]
#[UseResourceCollection(BindingTypeCollection::class)]
class BindingType extends Model
{
    public const string ID = 'id';

    public const string LABEL = 'label';

    protected $table = 'binding_types';

    protected $fillable = [self::LABEL];
}
