<?php

use Illuminate\Http\Request;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

/*Route::get('users', [\App\Http\Controllers\UserController::class, 'index',]);
Route::get('users/{id}', [\App\Http\Controllers\UserController::class, 'show',]);
Route::post('users', [\App\Http\Controllers\UserController::class, 'store']);
Route::put('users/{id}', [\App\Http\Controllers\UserController::class, 'update']);
Route::delete('users/{id}', [\App\Http\Controllers\UserController::class, 'destroy',]);*/

Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    Route::get('user', [\App\Http\Controllers\UserController::class, 'user']); //to get his data
    Route::get('chart', [\App\Http\Controllers\DashboardController::class, 'chart']);
    Route::put('users/info', [\App\Http\Controllers\UserController::class, 'updateInfo',]); //to update his info
    Route::put('users/password', [\App\Http\Controllers\UserController::class, 'updatePassword',]); //to update his password
    Route::post('upload', [\App\Http\Controllers\ImageController::class, 'upload']);
    Route::get('export', [\App\Http\Controllers\OrderController::class, 'export']);

       Route::apiResource('users', \App\Http\Controllers\UserController::class);
    Route::apiResource('roles', \App\Http\Controllers\RoleController::class);
    Route::apiResource('products', \App\Http\Controllers\ProductController::class);
    Route::apiResource('orders', \App\Http\Controllers\OrderController::class)->only('index', 'show');
    Route::apiResource('permissions', \App\Http\Controllers\PermissionController::class)->only('index');
});

