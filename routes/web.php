<?php

use Illuminate\Support\Facades\Route;

Route::get('/{link}', 'WebController@index')->where([
    'link' => '^[a-z0-9]{3,6}$'
])->name('transition');
