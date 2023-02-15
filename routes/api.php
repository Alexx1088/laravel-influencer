<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('user', [\App\Http\Controllers\AuthController::class, 'user']); //to get his data

// Admin
Route::prefix('admin')->group(function () {
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);

    Route::middleware(['auth:api', 'scope:admin'])->group(function () {
      //  Route::get('user', [\App\Http\Controllers\AuthController::class, 'user']); //to get his data
        Route::put('users/info', [\App\Http\Controllers\AuthController::class, 'updateInfo',]); //to update his info
        Route::put('users/password', [\App\Http\Controllers\AuthController::class, 'updatePassword',]); // to update his password
        Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);

        Route::get('chart', [\App\Http\Controllers\Admin\DashboardController::class, 'chart']);
        Route::post('upload', [\App\Http\Controllers\Admin\ImageController::class, 'upload']);
        Route::get('export', [\App\Http\Controllers\Admin\OrderController::class, 'export']);

        Route::apiResource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::apiResource('roles', \App\Http\Controllers\Admin\RoleController::class);
        Route::apiResource('products', \App\Http\Controllers\Admin\ProductController::class);
        Route::apiResource('orders', \App\Http\Controllers\Admin\OrderController::class)->only('index', 'show');
        Route::apiResource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->only('index');
    });
});

// Influencer
Route::prefix('influencer')->group(function () {
  /*  Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);*/
    Route::get('products', [\App\Http\Controllers\Influencer\ProductController::class, 'index']);

    Route::middleware(['auth:api', 'scope:influencer'])->group(function () {
        Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
       /* Route::get('user', [\App\Http\Controllers\AuthController::class, 'user']); //to get his data
        Route::put('users/info', [\App\Http\Controllers\AuthController::class, 'updateInfo',]); //to update his info
        Route::put('users/password', [\App\Http\Controllers\AuthController::class, 'updatePassword',]); // to update his password*/

        Route::post('links', [\App\Http\Controllers\Influencer\LinkController::class, 'store']);
        Route::get('stats', [\App\Http\Controllers\Influencer\StatsController::class, 'index']);
        Route::get('rankings', [\App\Http\Controllers\Influencer\StatsController::class, 'rankings']);
    });
});

// Checkout
Route::group([
    'prefix' => 'checkout',
], function () {
    Route::get('links/{code}', [\App\Http\Controllers\Checkout\LinkController::class, 'show']);
    Route::post('orders', [\App\Http\Controllers\Checkout\OrderController::class, 'store']);
    Route::post('orders/confirm', [\App\Http\Controllers\Checkout\OrderController::class, 'confirm']);
});
