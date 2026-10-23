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
    abort(403, 'Unauthorized access: You do not have permission to view this resource.');
})->name('test.403');

Route::get('/test-404', function () {
    abort(404, 'Page not found');
})->name('test.404');

Route::get('/test-500', function () {
    abort(500, 'Internal server error: The server encountered an unexpected condition.');
})->name('test.500');

/*
|--------------------------------------------------------------------------
| Simulate Server Error
|--------------------------------------------------------------------------
*/
Route::get('/simulate-error', function () {
    throw new Exception('This is a simulated critical server exception.');
})->name('simulate.error');

/*
|--------------------------------------------------------------------------
| Error Dashboard & Telemetry Management
|--------------------------------------------------------------------------
*/
Route::get('/error-dashboard', [ErrorDashboardController::class, 'index'])
    ->name('error.dashboard');

Route::get('/error-stats', [ErrorDashboardController::class, 'stats'])
    ->name('error.stats');

Route::get('/error-live-feed', [ErrorDashboardController::class, 'liveFeed'])
    ->name('error.live.feed');

Route::post('/api/error-report', [ErrorDashboardController::class, 'reportBrokenPage'])
    ->name('error.report.submit');

Route::post('/error-blacklist', [ErrorDashboardController::class, 'blacklistIp'])
    ->name('error.blacklist');

Route::delete('/error-unblock/{id}', [ErrorDashboardController::class, 'unblockIp'])
    ->name('error.unblock');

Route::post('/error-report-status/{id}', [ErrorDashboardController::class, 'updateReportStatus'])
    ->name('error.report.status');

Route::get('/error-export', [ErrorDashboardController::class, 'export'])
    ->name('error.export');

Route::delete('/error-delete/{id}', [ErrorDashboardController::class, 'destroy'])
    ->name('error.delete');

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
