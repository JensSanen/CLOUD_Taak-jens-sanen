<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WerfplanningController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/projects',function () {
    return view('projects');
});

Route::get('/projects/{id}/phases', function ($projectId) {
    return view('phases', ['projectId' => $projectId]);
});



