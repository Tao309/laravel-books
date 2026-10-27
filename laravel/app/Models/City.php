<?php

namespace App\Models;

use App\Http\Resources\Cities\CityDataResource;
use App\Http\Resources\Cities\CityResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;

#[UseResource(CityDataResource::class)]
#[UseResourceCollection(CityResourceCollection::class)]
class City extends Model
{
    public const string ID = 'id';

    public const string CODE = 'code';

    public const string TITLE = 'title';

    protected $table = 'cities';

    protected $fillable = [self::CODE, self::TITLE];
}
