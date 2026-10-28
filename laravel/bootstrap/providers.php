<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthorProvider;
use App\Providers\BindingTypeProvider;
use App\Providers\CityProvider;
use App\Providers\PublishingHouseProvider;
use App\Providers\ShopProvider;

return [
    AppServiceProvider::class,
    AuthorProvider::class,
    BindingTypeProvider::class,
    CityProvider::class,
    PublishingHouseProvider::class,
    ShopProvider::class,
];
