<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthorProvider;
use App\Providers\BindingTypeProvider;
use App\Providers\BookProvider;
use App\Providers\BookSeriesProvider;
use App\Providers\CityProvider;
use App\Providers\PaperTypeProvider;
use App\Providers\PublishingBrandProvider;
use App\Providers\PublishingHouseProvider;
use App\Providers\ShopProvider;

return [
    AppServiceProvider::class,
    AuthorProvider::class,
    BindingTypeProvider::class,
    BookProvider::class,
    BookSeriesProvider::class,
    CityProvider::class,
    PaperTypeProvider::class,
    PublishingBrandProvider::class,
    PublishingHouseProvider::class,
    ShopProvider::class,
];
