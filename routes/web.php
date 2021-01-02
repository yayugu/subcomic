<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlobController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/history', [HomeController::class, 'history']);
    Route::get('/comic/tag/{tag}', [ComicController::class, 'tagSearch']);
    Route::get('/comic/search', [ComicController::class, 'search']);
    Route::get('/comic/sync', [ComicController::class, 'sync']);
    Route::get('/comic/{id}/show', [ComicController::class, 'show']);
    Route::get('/comic/{id}', [ComicController::class, 'info']);
    Route::get('/comic', [ComicController::class, 'index']);
    Route::get('/image/{archiveFileId}/{index}', [BlobController::class, 'image']);
    Route::get('/fav', [FavoriteController::class, 'index']);
    Route::post('/fav', [FavoriteController::class, 'store']);
    Route::post('/fav/delete', [FavoriteController::class, 'delete']);
});

Route::get('/login', [AuthController::class, 'loginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/user/create', [UserController::class, 'create']);
Route::post('/user', [UserController::class, 'store']);
