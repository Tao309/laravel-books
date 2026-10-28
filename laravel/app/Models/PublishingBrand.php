<?php

namespace App\Models;

use App\Http\Resources\PublishingBrand\PublishingBrandDataResource;
use App\Http\Resources\PublishingBrand\PublishingBrandResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;

#[UseResource(PublishingBrandDataResource::class)]
#[UseResourceCollection(PublishingBrandResourceCollection::class)]
class PublishingBrand extends Model
{
    public const string ID = 'id';

    public const string NAME = 'name';

    protected $table = 'publishing_brands';

    protected $fillable = [self::ID, self::NAME];
}
