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

Route::get('/products/{search?}', 'Api\ProductController@index');

Route::resource('cart', 'Api\CartController');
Route::post('cart/checkout', 'Api\CartController@checkout');

Route::post('register', 'Api\UserController@register');
Route::post('login', 'Api\UserController@login');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'Api\UserController@details');
    Route::post('logout', 'Api\UserController@logout');

    Route::resource('products', 'Api\ProductController')->except([
        'index', 'show'
    ]);
    Route::prefix('products')->group(function(){
        Route::post('/list', 'Api\ProductController@list');
    });
});