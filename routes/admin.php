<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/


//note that the prefix is admin for all file route

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {

    Route::group(['namespace' => 'Dashboard', 'middleware' => 'auth:admin','prefix' => 'admin'], function () {

        Route::get('/', 'DashboardController@index')->name('admin.dashboard');  // the first page admin visits if authenticated
        Route::get('logout','LoginController@logout') -> name('admin.logout');

        Route::group(['prefix' => 'profile'], function () {
            Route::get('edit', 'ProfileController@editProfile')->name('edit.profile');
            Route::put('update', 'ProfileController@updateprofile')->name('update.profile');
         });

         ################################## users routes ######################################
        Route::group(['prefix' => 'users'], function () {
            Route::get('/','UsersController@index') -> name('admin.users');
            Route::get('create','UsersController@create') -> name('admin.user.create');
            Route::post('store','UsersController@store') -> name('admin.user.store');
            Route::get('edit/{id}','UsersController@edit') -> name('admin.user.edit');
            Route::post('update/{id}','UsersController@update') -> name('admin.user.update');
            Route::get('delete/{id}','UsersController@destroy') -> name('admin.user.delete');
        });

        ################################## end users    #######################################

        ################################## authors routes ######################################
        Route::group(['prefix' => 'authors'], function () {
            Route::get('/','AuthorsController@index') -> name('admin.authors');
            Route::get('create','AuthorsController@create') -> name('admin.author.create');
            Route::post('store','AuthorsController@store') -> name('admin.author.store');
            Route::get('edit/{id}','AuthorsController@edit') -> name('admin.author.edit');
            Route::get('search/','AuthorsController@getAuthor') -> name('admin.author.getAuthor');
            Route::post('update/{id}','AuthorsController@update') -> name('admin.author.update');
            Route::get('delete/{id}','AuthorsController@destroy') -> name('admin.author.delete');
        });

        ################################## end authors    #######################################

        ################################## books routes ######################################
        Route::group(['prefix' => 'books'], function () {
            Route::get('/','BooksController@index') -> name('admin.books');
            Route::get('create','BooksController@create') -> name('admin.book.create');
            Route::post('store','BooksController@store') -> name('admin.book.store');
            Route::get('edit/{id}','BooksController@edit') -> name('admin.book.edit');
            Route::get('search/','BooksController@getBook') -> name('admin.book.getBook');
            Route::post('update/{id}','BooksController@update') -> name('admin.book.update');
            Route::get('delete/{id}','BooksController@destroy') -> name('admin.book.delete');
        });

        ################################## end books    #######################################

    });

    Route::group(['namespace' => 'Dashboard', 'middleware' => 'guest:admin','prefix' => 'admin'], function () {

        Route::get('login', 'LoginController@login')->name('admin.login');
        Route::post('login', 'LoginController@postLogin')->name('admin.post.login');

    });

});
