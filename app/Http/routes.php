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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('test', 'NestedsetController@test');     //用于开发测试

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'ListController@index');     //composer模块说明

    //---验证码---\
    Route::get('captcha', 'CaptchaController@index');               //example
    Route::get('captcha/captcha', 'CaptchaController@captcha');
    Route::get('captcha/captcha1', 'CaptchaController@captcha1');
    Route::get('captcha/captcha2', 'CaptchaController@captcha2');
    //---验证码---/

    //---Redis---\
    Route::get('redis', 'RedisController@index');                   //example
    Route::get('setRedis', 'RedisController@setRedis');
    Route::get('delRedis', 'RedisController@delRedis');
    Route::get('getRedis', 'RedisController@getRedis');
    //---Redis---/

    //---无限分类---\
    Route::get('nestedset', 'NestedsetController@index');     //用于开发测试

    //---无限分类---/





});