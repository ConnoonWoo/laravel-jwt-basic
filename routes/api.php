<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});



Route::namespace('Api')->prefix('v1')->middleware('cors')->group(function () {
    Route::middleware('api.guard')->group(function () {
        // 注册
        Route::post('/users','UserController@store')->name('users.store');
        // 登录
        Route::post('/login','UserController@login')->name('users.login');

        Route::middleware('api.refresh')->group(function () {
            // 当前用户信息
            Route::get('/users/info', 'UserController@info')->name('users.info');
            // 用户列表
            Route::get('/listUsers', 'UserController@index')->name('users.index');
            // 单个用户
            Route::get('/users/{user}', 'UserController@show')->name('users.show');
            // 退出
            Route::get('/logout', 'UserController@logout')->name('users.logout');
        });
    });

    Route::middleware('admin.guard')->group(function () {
        // 注册
        Route::post('/admins','AdminController@store')->name('admins.store');
        // 登录
        Route::post('/admin/login','AdminController@login')->name('admins.login');

        Route::middleware('admin.refresh')->group(function () {
            // 当前用户信息
            Route::get('/admins/info', 'AdminController@info')->name('admins.info');
            // 用户列表
            Route::get('/listAdmins', 'AdminController@index')->name('admins.index');
            // 单个用户
            Route::get('/admins/{admin}', 'AdminController@show')->name('admins.show');
            // 退出
            Route::get('/admins/logout', 'AdminController@logout')->name('admins.logout');
        });
    });

});


