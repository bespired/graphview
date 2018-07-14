<?php

Route::get('/_/graphview', 'BuilderController@index');
Route::get('/_/graphview/build/create', 'BuilderController@create');

Route::get('/_/graphview/{suid}/build', 'BuilderController@show');
Route::get('/_/graphview/{suid}/build/scaffold', 'BuilderController@scaffold');
Route::get('/_/graphview/{suid}/build/delete', 'BuilderController@delete');
Route::get('/_/graphview/{suid}/build/export', 'BuilderController@export');

Route::post('/_/graphview/{suid}/build/save', 'BuilderController@update');
Route::post('/_/graphview/{suid}/build/import', 'BuilderController@import');

// Route::get('/prepare', 'PrepareController@show');
