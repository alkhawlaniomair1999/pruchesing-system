<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');

   
});
Route::get('/init', function () {
    return view('init');
});
