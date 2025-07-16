<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavouriteController;
//public Routes

Route::post('/login',[AuthController::class,'login']);
Route::post('/signup',[AuthController::class,'signup']);
Route::post('/forgot-password',[AuthController::class,'sendOtp']);
Route::post('/reset-password',[AuthController::class,'resetPassword']);

//Authenticated Routes
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout',[AuthController::class,'logout']);
});
//Admin Routes
Route::middleware(['auth:sanctum','role:admin'])->prefix('admin')->group(function(){
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    //Favourites Routes
    Route::apiResource('favourites', FavouriteController::class);
});


//user routes
Route::middleware(['auth:sanctum','role:user'])->group(function(){
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    //Favourites Routes
    Route::get('favourites', [FavouriteController::class, 'index']);
    Route::post('favourites', [FavouriteController::class, 'store']);
    Route::delete('favourites', [FavouriteController::class, 'destroy']);

});
