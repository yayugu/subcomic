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

Route::group(['before' => 'auth.basic'], function() {
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::get('/comic/tag/{tag}', ['as' => 'tagSearch', 'uses' => 'ComicController@tagSearch']);
    Route::get('/comic/{id}/show', ['as' => 'comicShow', 'uses' =>  'ComicController@show']);
    Route::get('/comic/{id}', ['as' => 'comicInfo', 'uses' =>  'ComicController@info']);
    Route::get('/image/{archiveFileId}/{index}', ['as' => 'comicImage', 'uses' =>  'BlobController@image']);
});