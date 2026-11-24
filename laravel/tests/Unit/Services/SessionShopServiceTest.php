<?php

namespace Tests\Unit\Services;

use App\Models\Shop;
use App\Services\SessionShopService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionShopServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_shop_and_get_shop_id(): void
    {
        $shop = Shop::factory()->create();
        $service = $this->app->make(SessionShopService::class);

        $service->setShop($shop);
        $this->app->instance(SessionShopService::class, $service);

        $this->assertSame($shop->id, SessionShopService::getShopId());
    }
}
