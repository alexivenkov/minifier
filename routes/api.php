<?php

use Illuminate\Support\Facades\Route;

Route::post('/generate', 'LinkController@generate')->name('generate');
Route::get('/limits', 'LinkController@limits')->name('limits');
Route::get('/transitions/{link}', 'LinkController@transitions')->name('transitions');
