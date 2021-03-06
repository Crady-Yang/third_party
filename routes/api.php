<?php

use Illuminate\Http\Request;

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

Route::any('create_email_tpl', 'Email\EmailController@createEmailTpl');
Route::any('create_single_tpl', 'Email\EmailController@createSingleTpl');
Route::any('replace_google_path', 'Email\EmailController@replaceGooglePath');
Route::any('replace_str_url', 'Email\EmailController@replaceStrUrl');
Route::any('send_all_email', 'Email\EmailController@sendAllEmail');
Route::any('send_email', 'Email\EmailController@sendEmail');

