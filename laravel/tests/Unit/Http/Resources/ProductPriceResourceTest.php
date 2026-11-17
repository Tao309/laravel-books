<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\ProductPrices\ProductPriceResource;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ProductPriceResourceTest extends TestCase
{
    public function test_resolves_price_and_formatted_date(): void
    {
        $price = new ProductPrice;
        $price->price = 1200;
        $price->date = Carbon::create(2026, 11, 10, 12, 0, 0);

        $data = ProductPriceResource::make($price)->resolve(new Request);

        $this->assertSame(1200, $data[ProductPrice::PARAM_PRICE]);
        $this->assertSame('2026-11-10 12:00:00', $data[ProductPrice::PARAM_DATE]);
    }

    public function test_foreign_keys_are_not_exposed(): void
    {
        $price = new ProductPrice;
        $price->price = 1200;
        $price->date = Carbon::create(2026, 11, 10, 12, 0, 0);

        $data = ProductPriceResource::make($price)->resolve(new Request);

        $this->assertArrayNotHasKey(ProductPrice::PARAM_PRODUCT_ID, $data);
        $this->assertArrayNotHasKey(ProductPrice::PARAM_USER_ID, $data);
    }
}
