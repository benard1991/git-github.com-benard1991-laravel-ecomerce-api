<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});


//BRAND CRUD
Route::group(['prefix' => 'brand'], function ($router) {

    Route::controller(BrandsController::class)->group(function () {
        Route::get('index', 'index');
        Route::get('show/{id}', 'show');
        Route::post('store', 'store');
        Route::put('updateBrand/{id}', 'updateBrand');
        Route::delete('deleteBrand/{id}', 'deleteBrand');
    });
});

//Category CRUD\
Route::group(['prefix' => 'category'], function ($router) {
    Route::controller(CategoryController::class)->group(function () {
        Route::get('index', 'index');
        Route::get('show/{id}', 'show');
        Route::post('store', 'store');
        Route::put('updateCategory/{id}', 'updateCategory');
        Route::delete('deleteCategory/{id}', 'deleteCategory');
    });
});


//Location CRUD
Route::group(['prefix' => 'location'], function ($router) {

    Route::controller(LocationController::class)->group(function () {
        Route::post('store', 'store');
        Route::put('updateLocation/{id}', 'updateLocation');
        Route::delete('deleteLocation/{id}', 'destroy');
    });
});


//PRODUCTS CRUD
Route::group(['prefix' => 'product'], function ($router) {
    Route::controller(ProductController::class)->group(function () {
        Route::get('index', 'index');
        Route::get('show/{id}', 'show');
        Route::post('store', 'store');
        Route::put('updateProduct/{id}', 'updateProduct');
        Route::delete('deleteProduct/{id}', 'destroy');
    });
});


//ORDER 
Route::group(['prefix' => 'order'], function ($router) {
    Route::controller(OrderController::class)->group(function () {
        Route::get('index', 'index');
        Route::get('show/{id}', 'show');
        Route::post('store', 'store');
        Route::get('getOrderItems/{id}', 'getOrderItems');
        Route::get('getUserOrders/{id}', 'getUserOrders');
        Route::post('changeOrderStaus/{id}', 'changeOrderStaus');
    });
});
