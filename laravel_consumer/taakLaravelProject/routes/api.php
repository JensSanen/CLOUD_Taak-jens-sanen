<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkedHoursController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\RackPositionController;


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

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{productId}', [ProductController::class, 'show']);
Route::get('/products/{productId}/supplier', [ProductController::class, 'showSupplier']);
Route::delete('/products/{productId}', [ProductController::class, 'destroy']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{productId}', [ProductController::class, 'update']);

Route::get('/suppliers', [SupplierController::class, 'index']);
Route::get('/suppliers/names', [SupplierController::class, 'indexNames']);
Route::get('/suppliers/{supplierId}/products', [SupplierController::class, 'indexProducts']);
Route::get('/suppliers/{supplierId}', [SupplierController::class, 'show']);
Route::post('/suppliers', [SupplierController::class, 'store']);
Route::delete('/suppliers/{supplierId}', [SupplierController::class, 'destroy']);
Route::put('/suppliers/{supplierId}', [SupplierController::class, 'update']);

Route::get('/racks', [RackController::class, 'index']);
Route::get('/racks/names', [RackController::class, 'indexNames']);
Route::get('/racks/{rackId}/products', [RackController::class, 'indexProducts']);
Route::get('/racks/{rackId}/emptyLocations', [RackController::class, 'indexEmptyLocations']);
Route::get('/racks/{rackId}', [RackController::class, 'show']);
Route::post('/racks', [RackController::class, 'store']);
Route::delete('/racks/{rackId}', [RackController::class, 'destroy']);
Route::put('/racks/{rackId}', [RackController::class, 'update']);


