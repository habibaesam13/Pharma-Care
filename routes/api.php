<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//public Routes
Route::post('/login',[AuthController::class,'login']);
Route::post('/signup',[AuthController::class,'signup']);
Route::post('/forgot-password',[AuthController::class,'sendOtp']);
Route::post('/reset-password',[AuthController::class,'resetPassword']);
//Authenticated Routes
Route::middleware('auth:sanctum')->group(function(){
Route::post('/logout',[AuthController::class,'logout']);
});


