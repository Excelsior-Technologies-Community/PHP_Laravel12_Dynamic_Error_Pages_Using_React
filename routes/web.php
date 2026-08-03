<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ErrorDashboardController;

/*
|--------------------------------------------------------------------------
| Home Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Test Error Pages
|--------------------------------------------------------------------------
*/

Route::get('/test-403', function () {
    abort(403, 'Unauthorized access');
})->name('test.403');

Route::get('/test-404', function () {
    abort(404, 'Page not found');
})->name('test.404');

Route::get('/test-500', function () {
    abort(500, 'Internal server error');
})->name('test.500');

/*
|--------------------------------------------------------------------------
| Simulate Server Error
|--------------------------------------------------------------------------
*/

Route::get('/simulate-error', function () {
    throw new Exception('This is a simulated server error.');
})->name('simulate.error');

/*
|--------------------------------------------------------------------------
| Error Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/error-dashboard', [ErrorDashboardController::class, 'index'])
    ->name('error.dashboard');

/*
|--------------------------------------------------------------------------
| Error Statistics API
|--------------------------------------------------------------------------
*/

Route::get('/error-stats', [ErrorDashboardController::class, 'stats'])
    ->name('error.stats');

/*
|--------------------------------------------------------------------------
| Export CSV
|--------------------------------------------------------------------------
*/

Route::get('/error-export', [ErrorDashboardController::class, 'export'])
    ->name('error.export');

/*
|--------------------------------------------------------------------------
| Delete Single Record
|--------------------------------------------------------------------------
*/

Route::delete('/error-delete/{id}', [ErrorDashboardController::class, 'destroy'])
    ->name('error.delete');

/*
|--------------------------------------------------------------------------
| Delete All Records
|--------------------------------------------------------------------------
*/

Route::delete('/error-delete-all', [ErrorDashboardController::class, 'deleteAll'])
    ->name('error.delete.all');

/*
|--------------------------------------------------------------------------
| Fallback Route (Must Be Last)
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    abort(404);
});
