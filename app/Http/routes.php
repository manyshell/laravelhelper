<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['web']], function () {
    Route::get('composerlist', 'ComposerListController@index');     //composer模块说明

    Route::get('captcha', 'CaptchaController@index');               //引用验证码
    Route::get('captcha/captcha', 'CaptchaController@captcha');     //验证码







});