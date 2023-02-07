<?php

use App\Http\Controllers\MentorController;
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

Route::get('mentors', [App\Http\Controllers\MentorController::class, 'index']);
Route::get('mentors/{id}', [App\Http\Controllers\MentorController::class, 'show']);
Route::post('mentors', [App\Http\Controllers\MentorController::class, 'create']);
Route::put('mentors/{id}', [App\Http\Controllers\MentorController::class, 'update']);
