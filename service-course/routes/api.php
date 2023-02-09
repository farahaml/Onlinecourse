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

//Mentor's Routes
Route::get('mentors', [App\Http\Controllers\MentorController::class, 'index']);
Route::get('mentors/{id}', [App\Http\Controllers\MentorController::class, 'show']);
Route::post('mentors', [App\Http\Controllers\MentorController::class, 'create']);
Route::put('mentors/{id}', [App\Http\Controllers\MentorController::class, 'update']);
Route::delete('mentors/{id}', [App\Http\Controllers\MentorController::class, 'destroy']);

//Course's Routes
Route::post('courses', [App\Http\Controllers\CourseController::class, 'create']);
Route::put('courses/{id}', [App\Http\Controllers\CourseController::class, 'update']);
Route::get('courses', [App\Http\Controllers\CourseController::class, 'index']);
Route::delete('courses/{id}', [App\Http\Controllers\CourseController::class, 'destroy']);

//Chapter's Routes
Route::post('chapters', [App\Http\Controllers\ChapterController::class, 'create']);
Route::put('chapters/{id}', [App\Http\Controllers\ChapterController::class, 'update']);