<?php

use App\Enums\RoleCode;
use App\Http\Controllers\Api\ArchiveProductController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BindingTypeController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookSeriesController;
use App\Http\Controllers\Api\BookUserDataController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\LinkBookController;
use App\Http\Controllers\Api\LinkSourceProductController;
use App\Http\Controllers\Api\MassSaveProductController;
use App\Http\Controllers\Api\PaperTypeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductUserDataController;
use App\Http\Controllers\Api\PublishingBrandController;
use App\Http\Controllers\Api\PublishingHouseController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\SourceProductController;
use App\Http\Controllers\Api\SourceProductsByBrandCodeController;
use App\Http\Controllers\Api\SourceProductTypeController;
use App\Http\Controllers\Api\SourceProductUserDataController;
use App\Http\Controllers\Api\UnlinkFromProductController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\UserSecurity;
use Illuminate\Support\Facades\Route;

if (app()->environment('production')) {
    Route::any('/', fn () => abort(404));
}

$registerApiResource = function (string $uri, string $controller, array $except = [], array $only = []) {
    $resourceMiddleware = [
        'index' => [RoleCode::ADMIN->value, RoleCode::USER->value],
        'show' => [RoleCode::ADMIN->value, RoleCode::USER->value],
        'store' => [RoleCode::ADMIN->value],
        'update' => [RoleCode::ADMIN->value],
        'destroy' => [RoleCode::ADMIN->value],
    ];

    $route = Route::apiResource($uri, $controller);

    if (! empty($except)) {
        $route->except($except);

        $resourceMiddleware = array_diff_key($resourceMiddleware, array_flip($except));
    }

    if (! empty($only)) {
        $route->only($only);

        $resourceMiddleware = array_intersect_key($resourceMiddleware, array_flip($only));
    }

    foreach ($resourceMiddleware as $method => $middleware) {
        $route->middlewareFor($method, CheckRole::ROLE . ':' . implode(',', $middleware));
    }
};

Route::middleware([UserSecurity::class, RoleCode::USER->getMiddlewarePath()])->group(function () use ($registerApiResource) {
    Route::get('/config', ConfigController::class);

    $registerApiResource('/cities', ProductController::class, except: ['create', 'edit', 'show']);
    $registerApiResource('/shops', ShopController::class, only: ['index', 'show']);

    // > books
    $registerApiResource('/paper_types', PaperTypeController::class, except: ['create', 'edit']);
    $registerApiResource('/binding_types', BindingTypeController::class, except: ['create', 'edit', 'show']);
    $registerApiResource('/book_series', BookSeriesController::class, except: ['create', 'edit', 'destroy']);
    $registerApiResource('/publishing_brands', PublishingBrandController::class, except: ['create', 'edit', 'destroy']);
    $registerApiResource('/publishing_houses', PublishingHouseController::class, except: ['create', 'edit', 'destroy']);
    $registerApiResource('/authors', AuthorController::class, except: ['create', 'edit']);
    $registerApiResource('/books', BookController::class, except: ['create', 'edit']);

    Route::get('/book_user_data/{book_id}', [BookUserDataController::class, 'show']);
    // < books

    // > source_products
    $registerApiResource('/source_product_types', SourceProductTypeController::class, except: ['create', 'edit']);
    $registerApiResource('/source_products', SourceProductController::class, except: ['create', 'edit']);

    Route::get('/source_products/brand/{brand_code}', SourceProductsByBrandCodeController::class);
    Route::get('/source_product_user_data/{source_product_id}', [SourceProductUserDataController::class, 'show']);
    // < source_products

    // > products
    $registerApiResource('/products', ProductController::class, except: ['create', 'edit']);
    Route::get('/product_user_data/{product_id}', [ProductUserDataController::class, 'show']);

    Route::post('/products/archive', ArchiveProductController::class);
    Route::post('/products/link_book/{product_id}/{book_id}', LinkBookController::class);
    Route::post('/products/link_source_product/{product_id}/{source_product_id}', LinkSourceProductController::class);
    Route::post('/products/unlink/{product_id}', UnlinkFromProductController::class);
    Route::post('/products/mass-save', MassSaveProductController::class);
    // < products

    // > brands
    $registerApiResource('/brands', BrandController::class, except: ['create', 'edit']);
    // < brands
});
