<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BindingTypeController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookSeriesController;
use App\Http\Controllers\Api\BookUserDataController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\LinkBookController;
use App\Http\Controllers\Api\LinkSourceProductController;
use App\Http\Controllers\Api\PaperTypeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductUserDataController;
use App\Http\Controllers\Api\PublishingBrandController;
use App\Http\Controllers\Api\PublishingHouseController;
use App\Http\Controllers\Api\SearchBooksController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\SourceProductController;
use App\Http\Controllers\Api\SourceProductTypeController;
use App\Http\Controllers\Api\SourceProductUserDataController;
use App\Http\Controllers\Api\UnlinkFromProductController;
use App\Http\Controllers\SearchSourceProductsController;
use App\Http\Middleware\ApiResponse;
use App\Http\Middleware\UserSecurity;
use Illuminate\Support\Facades\Route;

Route::middleware([UserSecurity::class, ApiResponse::class])->group(function () {
    Route::get('/config', ConfigController::class)->middleware(ApiResponse::class);

    Route::apiResource('/cities', CityController::class)->except(['create', 'edit', 'show']);
    Route::apiResource('/shops', ShopController::class)->only(['index', 'show']);

    // > books
    Route::get('/books/search/{value}', SearchBooksController::class);
    Route::apiResource('/paper_types', PaperTypeController::class)->except(['create', 'edit']);
    Route::apiResource('/binding_types', BindingTypeController::class)->except(['create', 'edit', 'show']);
    Route::apiResource('/books_series', BookSeriesController::class)->except(['create', 'edit', 'destroy']);
    Route::apiResource('/publishing_brands', PublishingBrandController::class)->except(['create', 'edit', 'destroy']);
    Route::apiResource('/publishing_houses', PublishingHouseController::class)->except(['create', 'edit', 'destroy']);
    Route::apiResource('/authors', AuthorController::class)->except(['create', 'edit']);
    Route::apiResource('/books', BookController::class)->except(['create', 'edit']);
    Route::get('/book_user_data/{book_id}', [BookUserDataController::class, 'show']);
    // < books

    // > source_products
    Route::get('/source_products/search/{value}', SearchSourceProductsController::class);
    Route::apiResource('/source_product_types', SourceProductTypeController::class)->except(['create', 'edit']);
    Route::apiResource('/source_products', SourceProductController::class)->except(['create', 'edit']);
    Route::get('/source_product_user_data/{source_product_id}', [SourceProductUserDataController::class, 'show']);
    // < source_products

    // > products
    Route::apiResource('/products', ProductController::class)->except(['create', 'edit']);
    Route::get('/product_user_data/{product_id}', [ProductUserDataController::class, 'show']);

    Route::post('/products/link_book/{product_id}/{book_id}', LinkBookController::class);
    Route::post('/products/link_source_product/{product_id}/{source_product_id}', LinkSourceProductController::class);
    Route::post('/products/unlink/{product_id}', UnlinkFromProductController::class);
    // < products
});
