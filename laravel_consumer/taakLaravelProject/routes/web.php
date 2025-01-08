<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WerfplanningController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/projects',function () {
    return view('projects');
});

Route::get('/projects/{projectId}/phases', function ($projectId) {
    return view('phases', ['projectId' => 2]);
});

Route::get('/projects/{projectId}/hours', function ($projectId) {
    return view('hours', ['projectId' => $projectId]);
});

Route::get('/projects/{projectId}/calculations', function ($projectId) {
    return view('calculations', ['projectId' => 1]);
});

Route::get('/stock', function () {
    return view('stock');
});




