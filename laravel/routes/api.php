<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BindingTypeController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookSeriesController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\PaperTypeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PublishingBrandController;
use App\Http\Controllers\Api\PublishingHouseController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\SourceProductController;
use App\Http\Controllers\Api\SourceProductTypeController;
use App\Http\Middleware\UserSecurity;
use Illuminate\Support\Facades\Route;

Route::middleware([UserSecurity::class])->group(function () {
    Route::apiResource('/cities', CityController::class)->except(['create', 'edit', 'show']);
    Route::apiResource('/shops', ShopController::class)->only(['index', 'show']);

    // > books
    Route::apiResource('/paper_types', PaperTypeController::class)->except(['create', 'edit']);
    Route::apiResource('/binding_types', BindingTypeController::class)->except(['create', 'edit', 'show']);
    Route::apiResource('/books_series', BookSeriesController::class)->except(['create', 'edit', 'destroy']);
    Route::apiResource('/publishing_brands', PublishingBrandController::class)->except(['create', 'edit', 'destroy']);
    Route::apiResource('/publishing_houses', PublishingHouseController::class)->except(['create', 'edit', 'destroy']);
    Route::apiResource('/authors', AuthorController::class)->except(['create', 'edit']);
    Route::apiResource('/books', BookController::class)->except(['create', 'edit']);
    // < books

    // > products
    Route::apiResource('/source_product_types', SourceProductTypeController::class)->except(['create', 'edit']);
    Route::apiResource('/source_products', SourceProductController::class)->except(['create', 'edit']);
    Route::apiResource('/products', ProductController::class)->except(['create', 'edit']);
    // < products
});
