<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    abort_if(app()->environment('production'), 404);

    return view('welcome');
});
