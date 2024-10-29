<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

// Home
Route::get('/', [EventController::class, 'index']);

// Create Events
Route::get('/events/create', [EventController::class, 'create'])->middleware('auth');
Route::post('/events', [EventController::class, 'store'])->middleware('auth');

// Show Events
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events/join/{id}', [EventController::class, 'joinEvent'])->middleware('auth');
Route::delete('/events/leave/{id}', [EventController::class, 'leaveEvent'])->middleware('auth');

// Dashboard
Route::get('/dashboard', [EventController::class, 'dashboard'])->middleware('auth');
Route::delete('/events/{id}', [EventController::class, 'destroy'])->middleware('auth');
Route::get('/events/show/{id}', [EventController::class, 'getEventData'])->middleware('auth');
Route::put('/events/update/{id}', [EventController::class, 'update'])->middleware('auth');

// Feedback Events
Route::get('/events/{id}/feedback', [EventController::class, 'createFeedback'])->middleware('auth');
Route::post('/events/{id}/feedback/create', [EventController::class, 'storeFeedback'])->middleware('auth');
