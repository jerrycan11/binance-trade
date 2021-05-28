<?php

use \App\Http\Controllers\HomeController;
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

Route::post('/getBuyRecords', [HomeController::class, 'getBuyRecords']);
Route::post('/getTradeRecords', [HomeController::class, 'getTradeRecords']);
Route::post('/getCoinBalance', [HomeController::class, 'getCoinBalance']);
Route::post('/placeMarketBuyOrder', [HomeController::class, 'placeMarketBuyOrder']);
Route::post('/placeMarketSellOrder', [HomeController::class, 'placeMarketSellOrder']);
Route::post('/getCoins', [HomeController::class, 'getCoins']);
Route::post('/removeCoin', [HomeController::class, 'removeCoin']);
Route::post('/addCoin', [HomeController::class, 'addCoin']);
