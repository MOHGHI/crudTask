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

Route::group(['middleware' => ['api','checkPassword'], 'namespace' => 'Api'], function () {

    Route::get('/books', 'BooksController@index');
    Route::get('/books/{id}', 'BooksController@getBookById');

    Route::group(['prefix' => 'admin','namespace'=>'Admin'],function (){
        Route::post('login', 'AuthController@login');
    });

    Route::group(['prefix' => 'user'],function (){
        Route::post('login', 'LoginController@login');
    });

});

Route::group(['middleware' => ['api','checkPassword','checkAdminToken:admin-api'], 'namespace' => 'Api'], function () {
    Route::post('/books/{id}', 'BooksController@bookUpdate');
    Route::delete('/books/{id}', 'BooksController@bookDestroy');
});
