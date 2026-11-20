<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\SourceProduct;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;

class SameProductsLoader
{
    /**
     * @var Collection<int, Product>
     */
    private Collection $products;

    public function load(Collection|SupportCollection|Product $products): void
    {
        if ($products instanceof Product) {
            $products = new Collection([$products]);
        } elseif (! $products instanceof Collection) {
            // Support Collection -> Eloquent: сохраняем элементы, получаем
            // предсказуемое поведение where/pluck и честный тип свойства
            $products = new Collection($products->all());
        }

        if ($products->isEmpty()) {
            return;
        }

        $this->products = $products;

        $bookIds = $products
            ->where(Product::PARAM_SOURCE_TYPE, Book::class)
            ->pluck(Product::PARAM_SOURCE_ID)
            ->unique()
            ->values()
            ->all();

        $sourceProductIds = $products
            ->where(Product::PARAM_SOURCE_TYPE, SourceProduct::class)
            ->pluck(Product::PARAM_SOURCE_ID)
            ->unique()
            ->values()
            ->all();

        $this->loadByBookIds($bookIds);
        $this->loadBySourceProductIds($sourceProductIds);
    }

    private function loadByBookIds(array $bookIds = []): void
    {
        $this->loadBySourceIds(Book::class, $bookIds);
    }

    private function loadBySourceProductIds(array $sourceProductIds = []): void
    {
        $this->loadBySourceIds(SourceProduct::class, $sourceProductIds);
    }

    private function loadBySourceIds(string $sourceType, array $sourceIds = []): void
    {
        if ($sourceIds === []) {
            return;
        }

        $subQuery = ProductPrice::query()
            ->selectRaw('product_id, MIN(price) AS min_price')
            ->where(ProductPrice::PARAM_USER_ID, Auth::id())
            ->whereNotNull(ProductPrice::PARAM_PRICE)
            ->groupBy(ProductPrice::PARAM_PRODUCT_ID);

        $sameProductsQuery = Product::query()
            ->withoutEagerLoads()
            ->from(Product::TABLE_NAME.' as pr')
            ->joinSub($subQuery, 'pp', function (JoinClause $join) {
                $join->on(
                    'pp.product_id',
                    '=',
                    'pr.'.Product::PARAM_ID
                );
            })
            ->where('pr.'.Product::PARAM_SOURCE_TYPE, $sourceType)
            ->whereIn('pr.'.Product::PARAM_SOURCE_ID, $sourceIds)
            ->orderBy('pp.min_price')
            ->select([
                'pr.'.Product::PARAM_ID,
                'pr.'.Product::PARAM_TITLE,
                'pr.'.Product::PARAM_SHOP_PRODUCT_ID,
                'pr.'.Product::PARAM_SHOP_PRODUCT_CODE,
                'pr.'.Product::PARAM_SOURCE_TYPE,
                'pr.'.Product::PARAM_SOURCE_ID,
                'pr.'.Product::PARAM_SHOP_ID,
                'pp.min_price',
            ])
            ->with(Product::PARAM_SHOP, 'productUserData')
            ->get()
            ->groupBy(Product::PARAM_SOURCE_ID);

        foreach ($this->products as $product) {
            if ($product->source_type !== $sourceType) {
                continue;
            }

            $currentSameProducts = $sameProductsQuery
                ->get($product->source_id, new Collection)
                ->reject(fn (Product $sameProduct) => $sameProduct->is($product))
                ->sortBy([
                    fn (Product $a, Product $b) => ($a->shop_id === $product->shop_id ? 0 : 1)
                        <=> ($b->shop_id === $product->shop_id ? 0 : 1),
                    fn (Product $a, Product $b) => $a->min_price <=> $b->min_price,
                ])
                ->values();

            $product->setRelation(Product::PARAM_MODEL_SAME_PRODUCTS, $currentSameProducts);
        }
    }
}
