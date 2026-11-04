<?php

namespace App\Models;

use App\Http\Resources\SourceProducts\SourceProductDataResource;
use App\Http\Resources\SourceProducts\SourceProductsDataResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseResource(SourceProductDataResource::class)]
#[UseResourceCollection(SourceProductsDataResourceCollection::class)]
class SourceProduct extends Model
{
    public const string TABLE_NAME = 'source_products';

    public const string PARAM_ID = 'id';

    public const string PARAM_TITLE = 'title';

    public const string PARAM_SOURCE_PRODUCT_TYPE = 'source_product_type';

    public const string PARAM_USER_CREATED = 'user_created';

    public const string PARAM_SOURCE_PRODUCT_TYPE_ID = 'source_product_type_id';

    public const string PARAM_USER_CREATED_ID = 'user_created_id';

    protected $table = self::TABLE_NAME;

    protected $hidden = [
        SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID,
        SourceProduct::PARAM_USER_CREATED_ID,
    ];

    protected $with = [
        'sourceProductType',
        'userCreated',
    ];

    protected $fillable = [
        self::PARAM_TITLE,
        self::PARAM_SOURCE_PRODUCT_TYPE_ID,
        self::PARAM_USER_CREATED_ID,
    ];

    public function sourceProductType(): BelongsTo
    {
        return $this->belongsTo(SourceProductType::class, self::PARAM_SOURCE_PRODUCT_TYPE_ID);
    }

    public function userCreated(): BelongsTo
    {
        return $this->belongsTo(User::class, self::PARAM_USER_CREATED_ID);
    }
}
