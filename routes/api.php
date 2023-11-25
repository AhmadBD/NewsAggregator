<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::middleware('throttle:api')->group(function () {
    Route::get('/news', 'App\Http\Controllers\NewsController@getNews');
    Route::get('/categories', 'App\Http\Controllers\NewsController@getCategories');
    Route::get('/countries', 'App\Http\Controllers\NewsController@getCountries');
});
