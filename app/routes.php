<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['before' => 'auth'], function() {
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::get('/history', ['as' => 'history', 'uses' =>  'HomeController@history']);
    Route::get('/comic/tag/{tag}', ['as' => 'tagSearch', 'uses' => 'ComicController@tagSearch']);
    Route::get('/comic/search', ['as' => 'comicSearch', 'uses' => 'ComicController@search']);
    Route::get('/comic/sync', ['as' => 'comicSync', 'uses' => 'ComicController@sync']);
    Route::get('/comic/{id}/show', ['as' => 'comicShow', 'uses' =>  'ComicController@show']);
    Route::get('/comic/{id}', ['as' => 'comicInfo', 'uses' =>  'ComicController@info']);
    Route::get('/comic', ['as' => 'comicIndex', 'uses' =>  'ComicController@index']);
    Route::get('/image/{archiveFileId}/{index}', ['as' => 'comicImage', 'uses' =>  'BlobController@image']);
    Route::get('/user/create', ['as' => 'userCreate', 'uses' =>  'UserController@create']);
    Route::post('/user', ['before' => 'csrf', 'as' => 'userStore', 'uses' =>  'UserController@store']);
    Route::get('/fav', ['as' => 'favorite', 'uses' => 'FavoriteController@index']);
    Route::post('/fav', ['before' => 'csrf', 'as' => 'favorite', 'uses' => 'FavoriteController@store']);
    Route::post('/fav/delete', ['before' => 'csrf', 'as' => 'favoriteDelete', 'uses' => 'FavoriteController@delete']);
});

Route::get('/login', ['as' => 'login', 'uses' => 'AuthController@loginForm']);
Route::post('/login', ['before' => 'csrf', 'as' => 'login', 'uses' => 'AuthController@login']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
