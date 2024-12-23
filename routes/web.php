<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportsController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ImportedDataController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard page
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User management routes
    Route::middleware('permission:user-management')->group(function () {
        Route::resource('users', UsersController::class)->except('show');

        Route::resource('permissions', PermissionsController::class)->except('show');
    });

    // Import routes
    Route::middleware('import-permissions')->group(function () {
        Route::get('/imports/create', [ImportsController::class, 'create'])->name('imports.create');
        Route::post('/imports', [ImportsController::class, 'store'])->name('imports.store');
    });

    Route::get('/imports', [ImportsController::class, 'index'])->name('imports.index');
    Route::get('/imports/{type}/{file}', [ImportsController::class, 'show'])->name('imports.show');

    // Imported data routes
    Route::delete('/imported-data/{type}/{id}', [ImportedDataController::class, 'destroy'])->name('imported-data.destroy');

    // Export routes
    Route::get('/exports/{type}/{file}', ExportController::class)->name('export');
});

Auth::routes();
