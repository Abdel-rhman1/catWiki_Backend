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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/get-category' , [App\Http\Controllers\BreadController::class , 'index']);

Route::post('/search' , [App\Http\Controllers\BreadController::class , 'seacrh']);

Route::post('/more_images' , [App\Http\Controllers\BreadController::class , 'getMoreImages']);


Route::get('/get_popualr' ,[App\Http\Controllers\BreadController::class , 'getPopular']);