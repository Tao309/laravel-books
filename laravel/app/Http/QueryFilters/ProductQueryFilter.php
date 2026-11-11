<?php

namespace App\Http\QueryFilters;

use App\DTOs\CollectionQueryDto;
use App\Models\Product;
use App\Services\SessionShopService;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryFilter extends AbstractQueryFilter
{
    public function applySearchFilter(Builder $query, $value): Builder
    {
        $titleField = Product::PARAM_TITLE;
        $value = mb_strtolower($value);

        $query->whereRaw("LOWER({$titleField}) LIKE ?", "%{$value}%");
        $query->limit(7);

        return $query;
    }

    public function apply(Builder $query, CollectionQueryDto $dto): Builder
    {
        $builder = parent::apply($query, $dto);

        $builder->where(Product::PARAM_SHOP_ID, '=', SessionShopService::getShopId());

        return $builder;
    }

    protected function applyIdsFilter(Builder $query, $value): Builder
    {
        return $query->whereIn(Product::PARAM_SHOP_PRODUCT_ID, $value);
    }
}
