<?php

use App\Http\Controllers\ProductCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

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

/*
 * Auth
 *  */
Route::middleware(['guest'])->group(function () {
    Route::post('login', Api\Auth\LoginController::class)->name('login');
    Route::post('register', Api\Auth\RegisterController::class)->name('register');
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', Api\Auth\LogoutController::class)->name('logout');
});

/*
 * Catalog
 * */
Route::apiResource('categories', ProductCategoryController::class)->only(['index']);
