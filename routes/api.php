<?php

Route::get('/project', 'ProjectController@getAll')->name('all-project');
Route::get('/project/{id}', 'ProjectController@show');
Route::post('/project', 'ProjectController@store');
Route::patch('/project/{id}', 'ProjectController@update');
Route::post('/project/{id}', 'ProjectController@destroy');

//List section
Route::post('/list-add', 'MultipleListController@store');//add list under project
Route::post('/board-list-update', 'MultipleListController@update');//Update list

//task listmanagement
Route::post('/add-task-task', 'TaskController@addNewTask');//add new node
Route::post('/task-list', 'TaskController@getAll');//get task list data
Route::post('/all-task-list', 'TaskController@getAllTask');//get task list data
Route::post('/task-list/add-task', 'TaskController@addTask');//update and add node
Route::post('/task-list/add-child-task', 'TaskController@addChildTask');//add child node
Route::post('/task-list/task-make-child', 'TaskController@makeChild');//make child node
Route::post('/task-list/reverse-child', 'TaskController@reverseChild');//reverse child node
Route::post('/task-list/update', 'TaskController@update');//reverse child node
Route::post('/task-list/delete-img', 'TaskController@deleteImg');//reverse child node
Route::post('/task-list/task-drag-drop', 'TaskController@taskDragDrop');//task-drag-drop


//task list tags related
Route::get('/task-list/all-tag', 'TagsController@index');//get all tag
Route::post('/task-list/add-tag', 'TagsController@store');//add tag
Route::post('/task-list/add-tag-to-multiple-task', 'TagsController@addTagToMultipleTask');//add dont forget tag
Route::post('/task-list/delete-tag', 'TagsController@destroy');//delete by tag title
Route::post('/task-list/update-tag', 'TagsController@update');//update tag
Route::post('/task-list/copy-cut-past', 'TaskController@CopyCutPast');//copy and past task node

// task list tasks
Route::post('/task-list/delete-task', 'TaskController@deleteTask');//delete task
Route::post('/task-list/move-task', 'TaskController@moveTask');//move-task
Route::get('/task-list/get-log/{task_id}', 'ActionLogController@getSingleTaskLog'); //reverse child node

//search task
Route::post('/task-list/suggest-user','SearchController@suggestUser'); //user suggest for search

//navigation management
Route::post('/allNavs','ProjectNavItemsController@store'); //add new nav item
Route::post('/nav-item/add-new','ProjectNavItemsController@store'); //add new nav item
Route::get('/nav-item/{project_id}','ProjectNavItemsController@index'); //all nav item
Route::post('/nav-item/update','ProjectNavItemsController@edit'); //all nav item
Route::post('/nav-list','ProjectNavItemsController@navList'); //all nav list item

//assign  user to task
Route::post('/task-list/assign-user','AssignedUserController@store'); //Assign user
Route::post('/task-list/assign-user-remove','AssignedUserController@delete'); //Assign user remove

//board Section
Route::post('/board-task','MultipleBoardController@index'); //get board item
Route::post('/board-save','MultipleBoardController@create'); //get board item
Route::post('/board-modify','MultipleBoardController@update'); //column info update
Route::post('/board-add', 'MultipleBoardController@store'); //add list under project
Route::get('/board-delete/{id}','MultipleBoardController@destroy'); //delete board
Route::get('/board-deleteAllCards/{id}','MultipleBoardController@deleteAllBoardWiseCards'); //delete board
Route::post('/board-hide/{id}', 'MultipleBoardController@hideColumn');
Route::post('/add-existing-tasks', 'MultipleBoardController@addExistingTasks');

Route::post('/card-add', 'MultipleBoardController@cardAdd');
Route::post('/card-sort', 'MultipleBoardController@cardSort');
Route::post('/column-sort', 'MultipleBoardController@columnSort');
Route::post('/card-update/{id}', 'MultipleBoardController@cardEdit');
Route::get('/card-delete/{id}','MultipleBoardController@cardDelete'); //delete card
Route::get('/board-task-delete/{id}','MultipleBoardController@existingTaskDelete'); //delete card
