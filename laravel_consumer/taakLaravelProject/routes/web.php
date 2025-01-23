<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/projects',function () {
    return view('projects');
});

Route::get('/projects/{projectId}/phases', function ($projectId) {
    return view('phases', ['projectId' => $projectId]);
});

Route::get('/projects/{projectId}/hours', function ($projectId) {
    return view('hours', ['projectId' => $projectId]);
});

Route::get('/projects/{projectId}/calculations', function ($projectId) {
    return view('calculations', ['projectId' => $projectId]);
});

Route::get('/projects/{projectId}/invoice', function ($projectId) {
    return view('invoice', ['projectId' => $projectId]);
});

Route::get('/stock', function () {
    return view('stock');
});

Route::get('/monitoring', function () {
    return view('monitor');
});
