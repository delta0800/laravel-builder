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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', 'Api/auth/LoginController@me')->name('login');

Route::get('/Api/generate/crud', 'Api\TableController@generator');

Route::resource('/epins', 'EpinController');


