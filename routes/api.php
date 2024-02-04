<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
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

Route::post('/cart/add/{productId}/{quantity}', [CartController::class, 'addItemInCart']);
Route::delete('/cart/{productId}', [CartController::class, 'deleteItemInCart']);
Route::get('/cart', [CartController::class, 'getCart']);

Route::get('/product', [ProductController::class, 'getProduct']);
