<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RevisionVehicleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\OwnerExisty;

Route::resource('/', HomeController::class);
Route::resource('/owner', ReportController::class);

Route::resource('/owners', OwnerController::class);
Route::post('/owners', [OwnerController::class, 'store'])->middleware(OwnerExisty::class);

Route::resource('/vehicles', VehicleController::class);
Route::post('/revisions/{idVehicle}', [RevisionVehicleController::class, 'store']);
Route::get('/revisions', [RevisionVehicleController::class, 'index']);
Route::patch('/revisions/{id}', [RevisionVehicleController::class, 'update']);
Route::delete('/revisions/{id}', [RevisionVehicleController::class, 'destroy']);