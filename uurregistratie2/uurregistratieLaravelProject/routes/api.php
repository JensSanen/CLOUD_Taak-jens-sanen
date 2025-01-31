<?php

use App\Http\Controllers\LaravelWorkersController;
use App\Http\Controllers\LaravelWorkedHoursController;

// REST API endpoints

// /api/workers
// Vraag alle werknemers op
Route::get('/workers', [LaravelWorkersController::class, 'index']);

// /api/workers/{workerId}
// Vraag een specifieke werknemer op
Route::get('/workers/{workerId}', [LaravelWorkersController::class, 'show']);

// /api/projects/{projectId}/workedHours
// Vraag alle gewerkte uren op voor een specifiek project
Route::get('/projects/{projectId}/workedHours', [LaravelWorkedHoursController::class, 'getProjectWorkedHours']);

// /api/projects/{projectId}/workedHours/{workerId}
// Vraag alle gewerkte uren op voor een specifiek project en werknemer
Route::get('/projects/{projectId}/workedHours/{workerId}', [LaravelWorkedHoursController::class, 'getProjectWorkerWorkedHours']);

// /api/workedHours/{workedHourId}
// Verwijder een specifieke gewerkte uren
Route::delete('/workedHours/{workedHourId}', [LaravelWorkedHoursController::class, 'deleteWorkedHours']);

// /api/projects/{projectId}/workedHours
// Voeg gewerkte uren toe voor een specifiek project
Route::post('/projects/{projectId}/workedHours', [LaravelWorkedHoursController::class, 'createWorkedHours']);
