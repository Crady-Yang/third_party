<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('twitter/callback','ThirdController@twitterCallback');
Route::get('google/callback','ThirdController@googleCallback');
Route::get('facebook/callback','ThirdController@facebookCallback');

Route::get('third_auth', 'ThirdController@thirdLogin')->name('third_login');
