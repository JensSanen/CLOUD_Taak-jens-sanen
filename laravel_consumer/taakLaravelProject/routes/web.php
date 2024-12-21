<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlaskUserController;
use App\Http\Controllers\ProjectController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/users',function () {
    return view('users');
});

Route::get('/projects',function () {
    return view('projects');
});

