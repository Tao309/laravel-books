<?php

namespace App\Http\QueryFilters;

use App\CQRS\Queries\SourceProducts\SourceProductsCollectionQueryDtoInterface;
use App\DTOs\CollectionQueryDtoInterface;
use App\Models\Brand;
use App\Models\SourceProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class SourceProductQueryFilter extends AbstractQueryFilter
{
    public function apply(Builder $query, CollectionQueryDtoInterface $dto): Builder
    {
        /**
         * @var $dto SourceProductsCollectionQueryDtoInterface
         */
        return parent::apply($query, $dto)
            ->when($dto->getBrandcode(), function ($query, $value) {
                $this->applyBrandCodeFilter($query, $value);
            });
    }

    public function applyBrandCodeFilter(Builder $query, $value): Builder
    {
        $query->leftJoin(
            Brand::TABLE_NAME,
            sprintf('%s.%s', SourceProduct::TABLE_NAME, SourceProduct::PARAM_BRAND_ID),
            '=',
            sprintf('%s.%s', Brand::TABLE_NAME, Brand::PARAM_ID)
        )
            ->where(sprintf('%s.%s', Brand::TABLE_NAME, Brand::PARAM_CODE), $value)
            ->select(sprintf('%s.*', SourceProduct::TABLE_NAME));

        return $query;
    }

    protected function applySearchFilter(Builder $query, $value): Builder
    {
        return $this->applySearchToQuery(
            $query,
            SourceProduct::PARAM_TITLE,
            $value,
            Config::get('app.t_price_checker.search_remove_words', [])
        );
    }
}
