<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\SameProductResource;
use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * SameProductResource — облегчённый ресурс для вложенных same_products:
 * скаляры + join'нутый min_price (raw-атрибут) и только shop/productUserData
 * из связей. resolve() с явным Request, модели собираются вручную (без БД).
 */
class SameProductResourceTest extends TestCase
{
    private function product(): Product
    {
        $product = new Product;
        $product->id = 5;
        $product->shop_product_id = '123456789012';
        $product->shop_product_code = null;
        $product->title = 'Кроссовки лето';
        $product->min_price = 800;

        return $product;
    }

    public function test_resolves_scalar_fields_with_joined_min_price(): void
    {
        $data = SameProductResource::make($this->product())->resolve(new Request);

        $this->assertSame(5, $data[Product::PARAM_ID]);
        $this->assertSame('123456789012', $data[Product::PARAM_SHOP_PRODUCT_ID]);
        $this->assertNull($data[Product::PARAM_SHOP_PRODUCT_CODE]);
        $this->assertSame('Кроссовки лето', $data[Product::PARAM_TITLE]);
        $this->assertSame(800, $data[Product::PARAM_MIN_PRICE]);
    }

    public function test_min_price_is_null_when_attribute_missing(): void
    {
        $product = $this->product();
        unset($product->min_price);

        $data = SameProductResource::make($product)->resolve(new Request);

        $this->assertNull($data[Product::PARAM_MIN_PRICE]);
    }

    /**
     * whenLoaded без загруженной связи возвращает MissingValue: resolve()
     * вырезает такие ключи из payload целиком (не null).
     */
    public function test_relations_dropped_when_not_loaded(): void
    {
        $data = SameProductResource::make($this->product())->resolve(new Request);

        $this->assertArrayNotHasKey(Product::PARAM_SHOP, $data);
        $this->assertArrayNotHasKey(Product::PARAM_PRODUCT_USER_DATA, $data);
    }

    public function test_loaded_relations_rendered_as_nested_resources(): void
    {
        $product = $this->product();

        $shop = new Shop;
        $shop->id = 1;
        $shop->type = 'ozon';
        $shop->domain = 'ozon.ru';

        $userData = new ProductUserData;
        $userData->available = true;

        $product->setRelation(Product::PARAM_SHOP, $shop);
        // имя связи — 'productUserData' (camelCase); PARAM_PRODUCT_USER_DATA
        // ('product_user_data') — это ключ в JSON, а не имя связи
        $product->setRelation('productUserData', $userData);

        $data = SameProductResource::make($product)->resolve(new Request);

        $this->assertSame(1, $data[Product::PARAM_SHOP][Shop::PARAM_ID]);
        $this->assertSame('ozon', $data[Product::PARAM_SHOP][Shop::PARAM_TYPE]);
        $this->assertTrue($data[Product::PARAM_PRODUCT_USER_DATA][ProductUserData::PARAM_AVAILABLE]);
    }

    /**
     * ProductResource отдаёт same_products через SameProductResource:
     * у вложенных элементов появляется min_price (раньше ключ отсекался
     * whenLoaded('prices') — slim-модели без загруженных цен).
     */
    public function test_product_resource_renders_same_products_via_same_product_resource(): void
    {
        $parent = new Product;
        $parent->id = 1;
        $parent->title = 'Кроссовки лето';

        $same = new Product;
        $same->id = 2;
        $same->shop_product_id = '999888777666';
        $same->shop_product_code = null;
        $same->title = 'Кроссовки осень';
        $same->min_price = 800;

        $parent->setRelation(
            Product::PARAM_SAME_PRODUCTS,
            new Collection([$same]),
        );

        $data = ProductResource::make($parent)->resolve(new Request);

        $items = $data[Product::PARAM_SAME_PRODUCTS];

        $this->assertCount(1, $items);
        $this->assertSame(2, $items[0][Product::PARAM_ID]);
        $this->assertSame('999888777666', $items[0][Product::PARAM_SHOP_PRODUCT_ID]);
        $this->assertSame(800, $items[0][Product::PARAM_MIN_PRICE]);
    }
}
