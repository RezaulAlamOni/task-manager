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

Route::get('/allcomments', 'HomeController@allComment');

Route::get('/clear', 'ClearController@show');

Route::get('/select', 'ClearController@show_select');

Route::get('/blank','ClearController@show_blank');

Route::get('/blank','BlankController@show');


Route::get('/list-pdf-create/{type}/{list_id}','MultipleListController@ListPdfCreate');

//Route::get('/projects','ProjectController@index');

/**
 * Test Cron Jobs Routes
 */
Route::get('reminder', 'ReminderSettingsController@sendDueDateEmail');
Route::get('report/daily/{project_id}', 'ReportEmailController@daily');
Route::get('report/weekly/{project_id}', 'ReportEmailController@weekly');
Route::get('report/monthly/{project_id}', 'ReportEmailController@monthly');


Route::get( '/{vue_route?}', 'ProjectController@index' )->where( 'vue_route', '(.*)' );



