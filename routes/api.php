<?php


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

Route::namespace('Api')
    ->group(function () {
        Route::post('auth/login', 'Auth\LoginController@login');
        Route::post('auth/register', 'Auth\RegisterController@register');

//        Route::middleware()
//            ->group(function () {
                Route::get('auth/user', 'Auth\LoginController@me');
                Route::post('auth/logout', 'Auth\LoginController@logout');

                Route::get('/projects', 'ProjectController@index');
                Route::post('/projects', 'ProjectController@store');
                Route::get('/projects/{project}', 'ProjectController@destroy');
                Route::get('/project/{slug}', 'ProjectController@show');

                Route::get('/project/{slug}/tables', 'TableController@index');
                Route::post('/tables', 'TableController@store');
                Route::put('/tables/edit/{table}', 'TableController@update');
                Route::delete('/tables/delete/{table}', 'TableController@destroy');
                Route::get('/tables/{tableId}', 'TableController@show');

                Route::post('/generate/crud', 'TableController@generator');

                Route::delete('/tableField/delete/{tableField}', 'TableFieldController@destroy');

                Route::get('/packages', 'PackageController@index');
 //           });
    });
