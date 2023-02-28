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

//Order's Routes
Route::post('orders', [App\Http\Controllers\OrderController::class, 'create']);
Route::get('orders', [App\Http\Controllers\OrderController::class, 'index']);

//Webhook's Routes
Route::post('webhook', [App\Http\Controllers\WebhookController::class, 'midtransHandler']);