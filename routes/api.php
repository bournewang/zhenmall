<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\ApiBaseController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ConfigController;
use App\Http\Controllers\API\GoodsController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\API\SalesController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WechatController;
use App\Http\Controllers\API\LogisticController;
use App\Http\Controllers\API\FileController;
use App\Http\Controllers\API\MappController;
use App\Http\Controllers\API\StoreController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ClerkController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\StockController;
use App\Http\Controllers\API\HealthController;
use App\Http\Controllers\API\BillingController;
use App\Http\Controllers\API\BalanceLogController;
use App\Http\Controllers\API\QuotaLogController;
use App\Http\Controllers\API\RedPacketController;
use App\Http\Controllers\API\WithdrawController;
use App\Http\Controllers\API\UnionPayController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::any('/wechat', 'WechatController@serve');
    Route::post('/logistic/notify',     [LogisticController::class, 'notify']);
// Route::group(['middleware' => []], function(){
    Route::get('/config/values',        [ConfigController::class, 'get']); //->name('config.get');
    Route::get('/provinces',             [RegionController::class, 'provinces']);
    Route::get('/provinces/{province_id}/cities',   [RegionController::class, 'cities']);
    Route::get('/cities/{city_id}/districts',       [RegionController::class, 'districts']);
    Route::get('/goods',                [GoodsController::class, 'index']);
    Route::get('/goods/{id}',           [GoodsController::class, 'detail']);
    Route::get('/categories',           [CategoryController::class, 'index']);
    Route::get('/banners',              [BannerController::class, 'index']);
    // Route::post('/user/wxapp/authorize',[WechatController::class, 'login']);
    Route::post('/wxapp/register',      [WechatController::class, 'register']);
    Route::post('/wxapp/login',         [WechatController::class, 'login']);
    Route::any ('/wechat/notify',       [WechatController::class, 'notify']);
    Route::any ('/wechat/withdraw-notify',       [WechatController::class, 'withdrawNotify']);
    Route::post('/mapp/notify',         [MappController::class, 'notify']);
    Route::any ('/unionpay/notify',     [UnionPayController::class, 'notify']);

    Route::get ('/reviews',              [ReviewController::class, 'index']);
    Route::get ('/reviews/{id}',         [ReviewController::class, 'detail']);

Route::group(['middleware' => ['auth:api']], function(){
    Route::put ('/user/type/{type}',   [UserController::class, 'type']);

    Route::get ('/user/info',           [UserController::class, 'info']);
    Route::post('/user/info',           [UserController::class, 'profile']);
    Route::post('/user/mobile',         [UserController::class, 'mobile']);
    Route::get ('/user/revenue',        [UserController::class, 'revenue']);
    Route::get ('/user/qrcode',         [UserController::class, 'qrcode']);
    Route::get ('/user/team',           [UserController::class, 'team']);
    Route::post ('/health',             [HealthController::class, 'create']);
    Route::get ('/health',              [HealthController::class, 'index']);
    Route::get ('/health/{id}',         [HealthController::class, 'show']);

    Route::post ('/file',               [FileController::class, 'upload']);

    Route::get ('/billing',             [BillingController::class, 'index']);
    Route::get ('/billing/items',       [BillingController::class, 'items']);

    Route::get   ('/cart',              [CartController::class, 'show']);
    Route::post  ('/cart/{goods_id}',   [CartController::class, 'add']);
    Route::put   ('/cart/{goods_id}',   [CartController::class, 'update']);
    Route::delete('/cart/{goods_id}',   [CartController::class, 'delete']);

    Route::get ('/orders',              [OrderController::class, 'index']);
    Route::get ('/orders/summary',      [OrderController::class, 'summary']);
    Route::get ('/orders/{id}',         [OrderController::class, 'show']);
    Route::post('/orders',              [OrderController::class, 'create']);
    Route::put ('/orders/{id}/place',   [OrderController::class, 'place']);
    Route::put ('/orders/{id}/receive', [OrderController::class, 'receive']);
    Route::post('/orders/{id}/review',  [OrderController::class, 'review']);

    Route::post  ('/goods/{id}/like',   [GoodsController::class, 'like']);
    Route::delete('/goods/{id}/like',   [GoodsController::class, 'dislike']);

    Route::get      ('/address',        [AddressController::class, 'index']);
    Route::get      ('/address/default',[AddressController::class, 'default']);
    Route::get      ('/address/current',[AddressController::class, 'current']);
    Route::get      ('/address/{id}',   [AddressController::class, 'show']);

    Route::post     ('/address',        [AddressController::class, 'create']);
    Route::put      ('/address/{id}',   [AddressController::class, 'update']);
    Route::delete   ('/address/{id}',   [AddressController::class, 'delete']);
    Route::post('/address/{id}/select', [AddressController::class, 'select']);

    Route::post     ('/stores',         [StoreController::class, 'create']);
    Route::get      ('/stores',         [StoreController::class, 'index']);
    Route::get      ('/stores/{id}',    [StoreController::class, 'show']);

    Route::get      ('/balance-logs',   [BalanceLogController::class, 'index']);
    Route::get      ('/quota-logs',     [QuotaLogController::class, 'index']);

    Route::get      ('/red-packets',    [RedPacketController::class, 'index']);
    Route::put      ('/red-packets/{id}',   [RedPacketController::class, 'open']);
    Route::post     ('/withdraw',       [WithdrawController::class, 'create']);
    Route::get      ('/withdraw',       [WithdrawController::class, 'index']);
    // Route::get      ('/customers',      [CustomerController::class, 'index']);
    // Route::get      ('/clerks',         [ClerkController::class, 'index']);

    // Route::get('/sales',                [SalesController::class, 'index']);
    // Route::get('/sales/{user_id}',      [SalesController::class, 'show']);
    // Route::get('/services',             [ServiceController::class, 'index']);
    // Route::get('/services/{user_id}',   [ServiceController::class, 'show']);

    // Route::get('/stocks',               [StockController::class, 'index']);
});
