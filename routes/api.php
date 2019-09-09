<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the API routes for your application as
| the routes are automatically authenticated using the API guard and
| loaded automatically by this application's RouteServiceProvider.
|
*/

Route::get('/project', 'ProjectController@getAll');
Route::get('/project/{id}', 'ProjectController@show');
Route::post('/project', 'ProjectController@store');
Route::patch('/project/{id}', 'ProjectController@update');
Route::post('/project/{id}', 'ProjectController@destroy');


Route::post('/list-add', 'MultipleListController@store');//add list under project

Route::post('/task-list', 'TaskController@getAll');//get task list data
Route::post('/task-list/add-task', 'TaskController@addTask');//update and add node
Route::post('/add-task-task', 'TaskController@addNewTask');//add new node

Route::post('/task-list/add-child-task', 'TaskController@addChildTask');//add child node
