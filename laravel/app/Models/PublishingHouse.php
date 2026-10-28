<?php

namespace App\Models;

use App\Http\Resources\PublishingHouses\PublishingHouseDataResource;
use App\Http\Resources\PublishingHouses\PublishingHouseResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;

#[UseResource(PublishingHouseDataResource::class)]
#[UseResourceCollection(PublishingHouseResourceCollection::class)]
class PublishingHouse extends Model
{
    public const string ID = 'id';

    public const string NAME = 'name';

    protected $table = 'publishing_houses';

    protected $fillable = [self::ID, self::NAME];
}
