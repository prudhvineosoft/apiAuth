<?php

use App\Http\Controllers\JWTController;
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
Route::group(['middleware'=>'api'],function($router){
    Route::post('/register',[JWTController::class,'register']);
    Route::post('/login',[JWTController::class,'login']);
    Route::post('/logout',[JWTController::class,'logout']);
    Route::post('/refresh',[JWTController::class,'refresh']);
    Route::get('/profile',[JWTController::class,'profile']);
});
