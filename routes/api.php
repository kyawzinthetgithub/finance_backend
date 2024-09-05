<?php

use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\Wallet\WalletController;
use App\Http\Controllers\Api\WalletType\WalletTypeController;
use App\Http\Controllers\Api\IncomeExpendCategory\CategoryController;


//auth
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


Route::middleware(['auth:sanctum','frontendapi'])->group(function () {
    //user
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/profile-edit/{id}',[AuthController::class,'edit']);
    Route::post('/profile-update/{id}',[AuthController::class,'update']);
    Route::post('/change-password/{id}',[AuthController::class,'changePassword']);

    //wallet_type
    Route::get('wallet-type',[WalletTypeController::class,'getAll']);

    //category (income_category and expend_category)
    Route::group(['controller' => CategoryController::class,'prefix' => 'category'],function(){
        Route::get('/','index');
    });

    //wallet
    Route::group(['prefix'=>'wallet','controller'=>WalletController::class],function(){
        Route::post('/create','store');
        Route::get('/user-wallet','UserWallet');
        Route::delete('/delete/{walletId}','destory');
        Route::get('/restore','getDeleteWallet');
        Route::post('/restore','restoreWallet');
    });

    //income
    Route::group(['prefix' => 'income','controller' => IncomeController::class],function(){
        Route::post('/create','store');
    });
});
