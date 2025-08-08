<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartItemController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartItemsController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\RequestServiceController;
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
    //Cart
    Route::get('carts', [CartController::class, 'index']);
    Route::get('carts/{user}', [CartController::class, 'show']); // Cart for specific user

    //prescriptions
    Route::apiResource('prescriptions', PrescriptionController::class)->except('create');

    //request services
    Route::apiResource('request-services', RequestServiceController::class);
});


//user routes
Route::middleware(['auth:sanctum','role:user'])->group(function(){
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    //Favourites Routes
    Route::get('favourites', [FavouriteController::class, 'index']);
    Route::post('favourites', [FavouriteController::class, 'store']);
    Route::delete('favourites', [FavouriteController::class, 'destroy']);
    // Cart - view current user's cart
    Route::get('cart', [CartController::class, 'show']);
    Route::delete('cart/{cart}',[CartController::class, 'destroy']);

    // Cart Items - add, update, and delete products in cart
    Route::post('cart/items', [CartItemController::class, 'store']); // Add item
    Route::put('cart/items/{cartItem}', [CartItemController::class, 'update']); // Update item
    Route::put('cart/items/by-product/{product_id}', [CartItemController::class, 'updateByProduct']);//update item by product

    Route::delete('cart/items/{cartItem}', [CartItemController::class, 'destroy']); // Remove item
    Route::delete('cart/items/by-product/{product_id}', [CartItemController::class, 'destroyByProduct']);// Remove item by ptoduct


    //prescriptions
    Route::apiResource('prescriptions', PrescriptionController::class);

    //request services
    Route::apiResource('request-services', RequestServiceController::class);

});
