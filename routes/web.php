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

Route::get('/', 'TasksController@index');

Route::resource('tasks', 'TasksController');

// user registration
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

// Login authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::put('login', 'Auth\LoginController@login')->name('login.put');
Route::delete('login', 'Auth\LoginController@login')->name('login.delete');

Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');
Route::post('logout', 'Auth\LoginController@logout')->name('logout.post');
Route::put('logout', 'Auth\LoginController@logout')->name('logout.put');
Route::delete('logout', 'Auth\LoginController@logout')->name('logout.delete');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
    Route::resource('tasks', 'TasksController', ['only' => ['store', 'destroy']]);
});