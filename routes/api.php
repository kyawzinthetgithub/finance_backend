<?php

use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Wallet\WalletController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/profile-edit/{id}',[AuthController::class,'edit']);
    Route::post('/profile-update/{id}',[AuthController::class,'update']);
    Route::post('/change-password/{id}',[AuthController::class,'changePassword']);

    Route::group(['prefix'=>'wallet','controller'=>WalletController::class],function(){
        Route::post('/create','store');
    });
});
