<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ErrorDashboardController;

// Home Page
Route::get('/', function () {
    return view('welcome');
});

// Test routes
Route::get('/test-403', function () {
    abort(403, 'Unauthorized access');
})->name('test.403');

Route::get('/test-404', function () {
    abort(404, 'Page not found');
})->name('test.404');

Route::get('/test-500', function () {
    abort(500, 'Internal server error');
})->name('test.500');

// Simulate server error
Route::get('/simulate-error', function () {
    throw new Exception('This is a simulated server error');
});

// Error Dashboard
Route::get('/error-dashboard', [ErrorDashboardController::class, 'index'])
    ->name('error.dashboard');

// Error Statistics API
Route::get('/error-stats', [ErrorDashboardController::class, 'stats']);

// Keep this LAST
Route::fallback(function () {
    abort(404);
});