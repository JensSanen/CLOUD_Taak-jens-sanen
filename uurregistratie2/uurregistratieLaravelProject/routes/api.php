<?php

use App\Http\Controllers\LaravelWorkersController;
use App\Http\Controllers\LaravelWorkedHoursController;

Route::get('/workers', [LaravelWorkersController::class, 'index']);
Route::get('/workers/{workerId}', [LaravelWorkersController::class, 'show']);
Route::get('/projects/{projectId}/workedHours', [LaravelWorkedHoursController::class, 'getProjectWorkedHours']);
Route::get('/projects/{projectId}/workedHours/{workerId}', [LaravelWorkedHoursController::class, 'getProjectWorkerWorkedHours']);

Route::delete('/workedHours/{workedHourId}', [LaravelWorkedHoursController::class, 'deleteWorkedHours']);
Route::post('/projects/{projectId}/workedHours', [LaravelWorkedHoursController::class, 'createWorkedHours']);
