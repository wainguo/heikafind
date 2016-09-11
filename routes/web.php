<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/list', 'ArticleController@getList');
Route::get('/prebuild', 'ArticleController@prebuild');
Route::get('/build', 'ArticleController@build');
Route::get('/edit', 'ArticleController@getEdit');
Route::get('/edit/{id}', 'ArticleController@getEdit');
Route::get('/listpreview', 'ArticleController@previewArticleList');
Route::get('/preview/{id}', 'ArticleController@previewArticle');
Route::post('/save', 'ArticleController@postSave');
Route::post('/cover', 'ArticleController@postImage');
Route::post('/upload', 'ArticleController@postUploadImage');
