<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\Ajax\GoodsController;
use App\Http\Controllers\Ajax\SalesOrderController;
use App\Http\Controllers\Ajax\PurchaseOrderController;
use App\Http\Controllers\Ajax\CustomerController;
use App\Http\Controllers\Ajax\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return	redirect('/nova');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::post('login',  [LoginController::class, 'authLogin'])->name('mall.login');
Route::get('sales/{id}/relation', [SalesController::class, 'relation']);

Route::group(['prefix' => 'ajax'], function(){
    Route::get('customers',                 [CustomerController::class, 'index']);
    Route::get('goods',                     [GoodsController::class, 'index']);
    Route::get('goods/options',             [GoodsController::class, 'options']);
    Route::post('sales-orders',             [SalesOrderController::class, 'create']);
    Route::get ('sales-orders/{id}',        [SalesOrderController::class, 'show']);
    Route::post('purchase-orders',          [PurchaseOrderController::class, 'create']);
    Route::get ('purchase-orders/{id}',     [PurchaseOrderController::class, 'show']);
    // Route::post('sales-orders/calculate',   [SalesOrderController::class, 'calculate']);
    Route::get ('user/info',                [UserController::class, 'info']);
    
});
