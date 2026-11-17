<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\ProductStocks\ProductStockResource;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ProductStockResourceTest extends TestCase
{
    public function test_resolves_qty_date_and_log(): void
    {
        $log = ['diffQty' => -5, 'newStatus' => 1];

        $stock = new ProductStock;
        $stock->qty = 42;
        $stock->date = Carbon::create(2026, 11, 10, 12, 0, 0);
        $stock->log = $log;

        $data = ProductStockResource::make($stock)->resolve(new Request);

        $this->assertSame(42, $data[ProductStock::PARAM_QTY]);
        $this->assertSame('2026-11-10 12:00:00', $data[ProductStock::PARAM_DATE]);
        $this->assertSame($log, $data[ProductStock::PARAM_LOG]);
    }

    public function test_null_log_stays_null(): void
    {
        $stock = new ProductStock;
        $stock->qty = 42;
        $stock->date = Carbon::create(2026, 11, 10, 12, 0, 0);

        $data = ProductStockResource::make($stock)->resolve(new Request);

        $this->assertNull($data[ProductStock::PARAM_LOG]);
    }

    public function test_foreign_keys_are_not_exposed(): void
    {
        $stock = new ProductStock;
        $stock->qty = 42;
        $stock->date = Carbon::create(2026, 11, 10, 12, 0, 0);

        $data = ProductStockResource::make($stock)->resolve(new Request);

        $this->assertArrayNotHasKey(ProductStock::PARAM_PRODUCT_ID, $data);
        $this->assertArrayNotHasKey(ProductStock::PARAM_USER_ID, $data);
    }
}
