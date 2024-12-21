<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlaskUserController;
use App\Http\Controllers\ProjectController;


Route::get('/projectsAPI', [ProjectController::class, 'index']);
Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
Route::get('/flaskusers', [FlaskUserController::class, 'index']);
Route::get('/users/{id}', [FlaskUserController::class, 'show']);
Route::post('/users', [FlaskUserController::class, 'store']);
Route::delete('/users/{id}', [FlaskUserController::class, 'destroy']);
