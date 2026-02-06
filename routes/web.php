<?php

use Illuminate\Support\Facades\Route;

// Home Page
Route::get('/', function () {
    return view('welcome');
});

// Test routes for error pages
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

// Catch-all route for undefined routes (will trigger 404)
Route::fallback(function () {
    return abort(404);
});