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

Route::get('/', 'WelcomeController@show');

Route::get('/home', 'HomeController@show');

Route::get('/clear', 'ClearController@show');

Route::get('/select', 'ClearController@show_select');

Route::get('/blank','ClearController@show_blank');

Route::get('/blank','BlankController@show');

//Route::get('/projects','ProjectController@index');



Route::get( '/{vue_route?}', 'ProjectController@index' )->where( 'vue_route', '(.*)' );



