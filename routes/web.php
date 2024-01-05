<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RevisionVehicleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;

Route::resource('/', HomeController::class);
Route::resource('/report', ReportController::class);

Route::resource('/owners', OwnerController::class);
Route::resource('/vehicles', VehicleController::class);
Route::post('/revision/{idVehicle}', [RevisionVehicleController::class, 'store']);
Route::get('/revision', [RevisionVehicleController::class, 'index']);
Route::delete('/revision/{id}', [RevisionVehicleController::class, 'destroy']);