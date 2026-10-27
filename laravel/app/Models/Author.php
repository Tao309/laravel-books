<?php

namespace App\Models;

use App\Http\Resources\Authors\AuthorResource;
use App\Http\Resources\Authors\AuthorResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;

#[UseResource(AuthorResource::class)]
#[UseResourceCollection(AuthorResourceCollection::class)]
class Author extends Model
{
    public const string ID = 'id';

    public const string FIRST_NAME = 'first_name';

    public const string LAST_NAME = 'last_name';

    protected $table = 'authors';

    protected $fillable = [self::FIRST_NAME, self::LAST_NAME];
}
