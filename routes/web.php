<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/events/create', function () {
    return view('event-create');
});
