<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SegmentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/events', [EventController::class, 'store']);
Route::get('/customers/{id}', [CustomerController::class, 'show']);
Route::post('/segments/query', [SegmentController::class, 'query']);
Route::get('/customers', [\App\Http\Controllers\Api\DashboardController::class, 'customers']);
Route::get('/events', [\App\Http\Controllers\Api\DashboardController::class, 'events']);
