<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubscriberController;


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

Route::post('login', [AuthController::class, 'login']);
Route::group(['middleware' => 'auth:sanctum','prefix' => 'users'], function ($router) {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('create', [UserController::class, 'store']);
    Route::get('/', [UserController::class, 'index']);
    Route::get('detail/{id}', [UserController::class, 'show']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('update/{id}', [UserController::class, 'update']);
    Route::post('update-profile', [UserController::class, 'updateProfile']);
    Route::post('delete/{id}', [UserController::class, 'destroy']);

});

Route::group(['middleware' => 'auth:sanctum','prefix' => 'articles'], function ($router) {
    Route::get('/', [ArticleController::class, 'index']);
    Route::post('create', [ArticleController::class, 'store']);
    Route::get('detail/{id}', [ArticleController::class, 'show']);
    Route::post('update/{id}', [ArticleController::class, 'update']);
    Route::post('delete/{id}', [ArticleController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:sanctum','prefix' => 'tags'], function ($router) {
    Route::get('/', [TagController::class, 'index']);
    Route::post('create', [TagController::class, 'store']);
    Route::get('detail/{id}', [TagController::class, 'show']);
    Route::post('update/{id}', [TagController::class, 'update']);
    Route::post('delete/{id}', [TagController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:sanctum','prefix' => 'authors'], function ($router) {
    Route::get('/', [AuthorController::class, 'index']);
    Route::post('create', [AuthorController::class, 'store']);
    Route::get('detail/{id}', [AuthorController::class, 'show']);
    Route::post('update/{id}', [AuthorController::class, 'update']);
    Route::post('delete/{id}', [AuthorController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:sanctum','prefix' => 'categories'], function ($router) {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('create', [CategoryController::class, 'store']);
    Route::get('detail/{id}', [CategoryController::class, 'show']);
    Route::post('update/{id}', [CategoryController::class, 'update']);
    Route::post('delete/{id}', [CategoryController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:sanctum','prefix' => 'subscribers'], function ($router) {
    Route::get('/', [SubscriberController::class, 'index']);
    Route::post('create', [SubscriberController::class, 'store']);
    Route::get('detail/{id}', [SubscriberController::class, 'show']);
    Route::post('update/{id}', [SubscriberController::class, 'update']);
    Route::post('delete/{id}', [SubscriberController::class, 'destroy']);
});