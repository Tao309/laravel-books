<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BindingTypeController;
use App\Http\Controllers\Api\BookSeriesController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\PublishingBrandController;
use App\Http\Controllers\Api\PublishingHouseController;
use App\Http\Controllers\Api\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/authors', AuthorController::class)->except(['create', 'edit']);
Route::apiResource('/binding_types', BindingTypeController::class)->except(['create', 'edit', 'show']);
Route::apiResource('/cities', CityController::class)->except(['create', 'edit', 'show']);
Route::apiResource('/shops', ShopController::class)->only(['index', 'show']);
Route::apiResource('/publishing_houses', PublishingHouseController::class)->except(['create', 'edit', 'destroy']);
Route::apiResource('/publishing_brands', PublishingBrandController::class)->except(['create', 'edit', 'destroy']);
Route::apiResource('/books_series', BookSeriesController::class)->except(['create', 'edit', 'destroy']);
