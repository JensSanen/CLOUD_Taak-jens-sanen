<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkedHoursController;

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{projectId}', [ProjectController::class, 'show']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::delete('/projects/{projectId}', [ProjectController::class, 'destroy']);
Route::put('/projects/{projectId}', [ProjectController::class, 'update']);

Route::get('/projects/{projectId}/phases', [PhaseController::class, 'index']);
Route::get('/projects/{projectId}/phases/{phaseId}', [PhaseController::class, 'show']);
Route::post('/projects/{projectId}/phases', [PhaseController::class, 'store']);
Route::delete('/projects/{projectId}/phases/{phaseId}', [PhaseController::class, 'destroy']);
Route::put('/projects/{projectId}/phases/{phaseId}', [PhaseController::class, 'update']);

Route::get('/weather/{location}', [WeatherController::class, 'getWeatherForecast']);

Route::get('/workers', [WorkerController::class, 'index']);
Route::get('/workers/{workerId}', [WorkerController::class, 'show']);

Route::get('/projects/{projectId}/hours', [WorkedHoursController::class, 'index']);
Route::get('/projects/{projectId}/hours/{workerId}', [WorkedHoursController::class, 'indexWorkers']);
Route::delete('/hours/{whId}', [WorkedHoursController::class, 'destroy']);
Route::post('/projects/{projectId}/hours', [WorkedHoursController::class, 'store']);
