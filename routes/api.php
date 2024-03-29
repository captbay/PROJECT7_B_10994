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

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');
Route::group(['middleware' => 'auth:api'], function () {
    // Customer authentication
    Route::get('customer', 'Api\CustomerController@index');
    Route::get('customer/{id}', 'Api\CustomerController@show');
    Route::post('customer', 'Api\CustomerController@store');
    Route::put('customer/{id}', 'Api\CustomerController@update');
    Route::delete('customer/{id}', 'Api\CustomerController@destroy');

    // Product authentication
    Route::get('product', 'Api\ProductController@index');
    Route::get('product/{id}', 'Api\ProductController@show');
    Route::post('product', 'Api\ProductController@store');
    Route::put('product/{id}', 'Api\ProductController@update');
    Route::delete('product/{id}', 'Api\ProductController@destroy');

    // User authentication
    Route::get('user', 'Api\UserController@index');
    Route::get('user/{id}', 'Api\UserController@show');
    // tidak ada metod creat di user
    // Route::post('user', 'Api\UserController@store');
    Route::post('user/{id}', 'Api\UserController@update');
    Route::delete('user/{id}', 'Api\UserController@destroy');

    // logout
    Route::post('logout', 'Api\AuthController@logout');
});