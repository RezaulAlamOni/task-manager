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

//Route::get('/projects','ProjectController@index');
Route::prefix('api')->group(function () {
    Route::get('/project', 'ProjectController@getAll');
    Route::get('/project/{id}', 'ProjectController@show');
    Route::post('/project', 'ProjectController@store');
    Route::patch('/project/{id}', 'ProjectController@update');
    Route::post('/project/{id}', 'ProjectController@destroy');

    Route::get('/task-list/{id}', 'TaskController@getAll');//get task list data
    Route::post('/task-list/add-task', 'TaskController@addTask');//add node


});


Route::get( '/{vue_route?}', 'ProjectController@index' )->where( 'vue_route', '(.*)' );



