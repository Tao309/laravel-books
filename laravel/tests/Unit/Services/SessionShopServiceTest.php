<?php

namespace Tests\Unit\Services;

use App\Enums\ShopType;
use App\Models\Shop;
use App\Services\SessionShopService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tests\TestCase;

/**
 * Чистые unit-тесты SessionShopService: сессионный магазин (singleton)
 * без БД — модели Shop собираются через forceFill (id/type не fillable).
 */
class SessionShopServiceTest extends TestCase
{
    private SessionShopService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(SessionShopService::class);
    }

    private function shop(int $id, string $type): Shop
    {
        return (new Shop)->forceFill([
            Shop::PARAM_ID => $id,
            Shop::PARAM_TYPE => $type,
        ]);
    }

    public function test_set_shop_fluently_returns_self(): void
    {
        $this->assertSame($this->service, $this->service->setShop($this->shop(1, ShopType::Ozon->value)));
    }

    public function test_get_shop_id_returns_set_shop_id(): void
    {
        $this->service->setShop($this->shop(7, ShopType::Ozon->value));

        $this->assertSame(7, SessionShopService::getShopId());
    }

    public function test_get_shop_id_without_shop_throws_access_denied(): void
    {
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Shop for session is not set');

        SessionShopService::getShopId();
    }

    public function test_get_shop_id_with_unsaved_shop_throws_access_denied(): void
    {
        $this->service->setShop(new Shop);

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Shop for session is not set');

        SessionShopService::getShopId();
    }

    public function test_is_wildberries_shop_type_matches_shop_type(): void
    {
        $this->service->setShop($this->shop(1, ShopType::Wildberries->value));
        $this->assertTrue($this->service->isWildberriesShopType());

        $this->service->setShop($this->shop(1, ShopType::Ozon->value));
        $this->assertFalse($this->service->isWildberriesShopType());
    }
}
