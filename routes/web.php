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

Route::get('blog/titles/{query?}', 'BlogController@getTitles');
Route::resource('blog', 'BlogController');

//mail
Route::get('contact', 'ContactController@showForm');
Route::post('contact', 'ContactController@sendContactInfo');

//site map
Route::get('sitemap.xml', 'BlogController@siteMap');

Route::group(['namespace' => 'Auth'], function () {
    Route::get('login', 'LoginController@showLoginForm');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout');
});

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::resource('post', 'PostController', ['except' => 'show']);
    Route::resource('tag', 'TagController', ['except' => 'show']);

    //上传路由
    Route::group(['prefix' => 'upload'], function () {
        Route::get('/', 'UploadController@index');
        Route::post('file', 'UploadController@uploadFile');
        Route::delete('file', 'UploadController@deleteFile');
        Route::post('folder', 'UploadController@createFolder');
        Route::delete('folder', 'UploadController@deleteFolder');

        //qiniu
        Route::post('qiniu', 'UploadController@uploadQiniu');
    });
});