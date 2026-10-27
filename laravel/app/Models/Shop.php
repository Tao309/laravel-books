<?php

namespace App\Models;

use App\Http\Resources\Shops\ShopDataResource;
use App\Http\Resources\Shops\ShopResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;

#[UseResource(ShopDataResource::class)]
#[UseResourceCollection(ShopResourceCollection::class)]
class Shop extends Model
{
    public const string ID = 'id';

    public const string TYPE = 'type';

    public const string DOMAIN = 'domain';

    public const string URL = 'url';

    protected $table = 'shops';
}
