<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\GovernmentController;
use App\Http\Controllers\Api\Admin\StoreController;
use App\Http\Controllers\Api\Admin\UsersController;
use App\Http\Controllers\Api\App\Auth\GoogleAuthController;
use App\Http\Controllers\Api\Store\OfferController;
use App\Http\Controllers\Api\User\UserCategoriesController;
use App\Http\Controllers\Api\User\UserFavoritesController;
use App\Http\Controllers\Api\User\UserGovermentsController;
use App\Http\Controllers\Api\User\UserOffersController;
use App\Http\Controllers\Api\User\UserProfileController;
use App\Http\Controllers\Api\User\UserStoresController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/google-login', [GoogleAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [GoogleAuthController::class, 'logout']);


// app routes
Route::middleware(['auth:sanctum', 'role:store'])->prefix('store')->group(function () {
    Route::apiResource('offers', OfferController::class);
});

Route::middleware(['auth:sanctum', 'role:user,store'])->prefix('user')->group(function () {
    Route::get('offers/by-government/{government_id}',[UserOffersController::class, 'byGovernment']);
    Route::get('governments', [UserGovermentsController::class, 'index']);
    Route::get('categories', [UserCategoriesController::class, 'index']);
    Route::get('stores/by-category-and-government/{category_id}/{government_id}', [UserStoresController::class, 'byCategoryAndGovernment']);
    Route::get('offers/by-category-and-government/{category_id}/{government_id}', [UserOffersController::class, 'byCategoryAndGovernment']);
    Route::get('profile', [UserProfileController::class, 'index']);
    Route::post('offers/{offer_id}/toggle-favorite', [UserFavoritesController::class, 'toggle']);

});

// admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::apiResource('governments', GovernmentController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::post('users/{id}/make-store', [StoreController::class, 'updateUserToStore']);
    Route::get('users', [UsersController::class, 'index']);
    Route::get('profile', [UserProfileController::class, 'index']);

});
