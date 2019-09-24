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
Route::post('/task-list/task-make-child', 'TaskController@makeChild');//make child node
Route::post('/task-list/reverse-child', 'TaskController@reverseChild');//reverse child node
Route::post('/task-list/add-dont-forget-tag', 'TaskController@addDontForgetTag');//add dont forget tag
Route::post('/task-list/update', 'TaskController@update');//reverse child node
Route::post('/task-list/delete-img', 'TaskController@deleteImg');//reverse child node

Route::get('/task-list/all-tag', 'TagsController@index');//add tag
Route::post('/task-list/add-tag', 'TagsController@store');//add tag
Route::post('/task-list/delete-tag', 'TagsController@destroy');//add tag
Route::post('/task-list/update-tag', 'TagsController@update');//add tag

Route::post('/task-list/copy-cut-past', 'TaskController@CopyCutPast');//copy and past task node
Route::post('/task-list/delete-task', 'TaskController@deleteTask');//delete task
Route::post('/task-list/move-task', 'TaskController@moveTask');//move-task

Route::post('/nev-item/add-new','ProjectNavItemsController@store'); //add new nev item
Route::get('/nev-item/{project_id}','ProjectNavItemsController@index'); //all nev item
Route::post('/nev-item/update','ProjectNavItemsController@edit'); //all nev item

Route::get('/task-list/get-log/{task_id}', 'ActionLogController@getSingleTaskLog');//reverse child node


//board Section
Route::post('/board-task','MultipleBoardController@index'); //get board item
Route::post('/board-save','MultipleBoardController@create'); //get board item
Route::post('/board-modify','MultipleBoardController@update'); //column info update

Route::post('/card-add', 'MultipleBoardController@cardAdd');

Route::delete('/delete-board/{id}','MultipleBoardController@destroy'); //delete board 

Route::post('/board-add', 'MultipleBoardController@store'); //add list under project

