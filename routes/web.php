<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index')->name('home');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/** Roles **/
Route::group(['prefix' => 'roles'], function () {

    Route::get('/', 'RoleController@index')->name('roles');

    Route::post('/api', 'RoleController@api')->name('roles.api');

    Route::patch('/update', 'RoleController@update')->name('roles.update');

    Route::post('/store', 'RoleController@store')->name('roles.add');

    Route::delete('/delete', 'RoleController@destroy')->name('roles.destroy');

});

/** Users **/
Route::group(['prefix' => 'users'], function () {

    Route::get('/', 'UserController@index')->name('users');

    Route::post('/api', 'UserController@api')->name('users.api');

    Route::patch('/update', 'UserController@update')->name('users.update');

    Route::post('/store', 'UserController@store')->name('users.add');

    Route::delete('/delete', 'UserController@destroy')->name('users.destroy');

});

/** Profile **/
Route::group(['prefix' => 'profile'], function() {

    Route::patch('/update', 'ProfileController@update')->name('profile.update');

});
