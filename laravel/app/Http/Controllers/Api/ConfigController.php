<?php

namespace App\Http\Controllers\Api;

use App\CacheProviders\BindingTypesCacheProvider;
use App\CacheProviders\BookSeriesCacheProvider;
use App\CacheProviders\PublishingBrandsCacheProvider;
use App\CacheProviders\PublishingHousesCacheProvider;
use App\CacheProviders\SourceProductsTypesCacheProvider;
use App\Enums\ShopType;
use App\Http\Response\ResponseContext;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

class ConfigController extends ApiController
{
    public function __construct(
        private readonly BookSeriesCacheProvider $bookSeriesCacheProvider,
        private readonly PublishingBrandsCacheProvider $bookPublishingBrandCacheProvider,
        private readonly PublishingHousesCacheProvider $bookPublishingHouseCacheProvider,
        private readonly BindingTypesCacheProvider $bindingTypesCacheProvider,
        private readonly SourceProductsTypesCacheProvider $sourceProductTypesCacheProvider,
        ResponseContext $context,
        ?string $resourceClass,
        ?ServiceInterface $service = null,
    ) {
        parent::__construct($context, $service, $resourceClass);
    }

    public function __invoke(): JsonResponse
    {
        return $this->toJson([
            'processed_at' => date('Y-m-d H:i:s'),
            'source_product_types' => $this->sourceProductTypesCacheProvider->get(),
            'book_binding_types' => $this->bindingTypesCacheProvider->get(),
            'book_publishing_houses' => $this->bookPublishingHouseCacheProvider->get(),
            'book_publishing_brands' => $this->bookPublishingBrandCacheProvider->get(),
            'book_series' => $this->bookSeriesCacheProvider->get(),
            'shop_urls' => [
                ShopType::Wildberries->value => Config::get('shop_urls.wb'),
                ShopType::Ozon->value => Config::get('shop_urls.ozon'),
                ShopType::ChitaiGorod->value => Config::get('shop_urls.chitai_gorod'),
                ShopType::Ffan->value => Config::get('shop_urls.ffan'),
                ShopType::Knigofan->value => Config::get('shop_urls.knigofan'),
            ],
            'access_rights' => $this->getAccessRights(),
            'app_version' => Config::get('app_version') ?? 'not-found-version',
        ]);
    }

    // @todo добавить реальные права
    private function getAccessRights(): array
    {
        return [
            'product' => [
                'update' => true,
                'create' => true,
                'limit_enabled' => false,
                'limit' => null,
            ],
            'book' => [
                'update' => true,
                'create' => true,
                'limit' => null,
                'add_publishing_house' => true,
                'add_publishing_brand' => true,
                'add_series' => true,
            ],
            'source_product' => [
                'enabled' => true,
                'create' => true,
                'update' => true,
                'limit' => null,
            ],
            'shop' => [
                'list' => [
                    'ozon',
                    'wildberries',
                    'chitai-gorod',
                    'ffan',
                    'knigofan',
                ],
            ],
        ];
    }
}
