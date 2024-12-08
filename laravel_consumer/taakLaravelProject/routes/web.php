<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlaskUserController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/users',function () {
    return view('users');
});

Route::get('/flaskusers', [FlaskUserController::class, 'index']);
Route::get('/users/{id}', [FlaskUserController::class, 'show']);
Route::post('/users', [FlaskUserController::class, 'store']);
Route::delete('/users/{id}', [FlaskUserController::class, 'destroy']);
