<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/categories-wines', [App\Http\Controllers\Api\StoreApiController::class, 'categoriesWines']);
Route::get('/hero-section', [App\Http\Controllers\Api\StoreApiController::class, 'heroSection']);
Route::get('/collection-wines', [App\Http\Controllers\Api\StoreApiController::class, 'collectionWines']);
Route::get('/products', [App\Http\Controllers\Api\StoreApiController::class, 'products']);
Route::get('/packs', [App\Http\Controllers\Api\StoreApiController::class, 'packs']);
Route::get('/categories', [App\Http\Controllers\Api\StoreApiController::class, 'categories']);
Route::post('/shipping/calculate', [App\Http\Controllers\Api\StoreApiController::class, 'calculateShipping']);

Route::post('/payment/init', [App\Http\Controllers\PaymentController::class, 'init']);
Route::get('/payment/confirm-mock', [App\Http\Controllers\PaymentController::class, 'confirmMock']);
Route::get('/payment/return', [App\Http\Controllers\PaymentController::class, 'handleReturn']);
Route::post('/payment/notify', [App\Http\Controllers\PaymentController::class, 'handleNotification']);

Route::post('/coupons/validate', [App\Http\Controllers\CouponController::class, 'validateCoupon']);
Route::get('/store-banners', [App\Http\Controllers\Api\StoreBannerController::class, 'index']);
