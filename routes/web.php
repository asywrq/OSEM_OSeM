<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;


// Redirect root to dashboard if logged in, else login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'user') {
            return redirect()->route('user.my-vehicle');
        }
        return view('dashboard');
    })->name('dashboard');

    // Admin only
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', function () { return view('admin.users'); })->name('users');
        Route::get('/offences', function () { return view('admin.offences'); })->name('offences');
        Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles');
        Route::patch('/vehicles/{vehicle}/approve', [VehicleController::class, 'approve'])->name('vehicles.approve');
        Route::patch('/vehicles/{vehicle}/reject', [VehicleController::class, 'reject'])->name('vehicles.reject');
    });

    // Officer only
Route::middleware(['role:officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/vehicle-applications', [VehicleController::class, 'applications'])->name('vehicle-applications');
    Route::patch('/vehicle-applications/{vehicle}/approve', [VehicleController::class, 'approveApplication'])->name('vehicle-applications.approve');
    Route::patch('/vehicle-applications/{vehicle}/reject', [VehicleController::class, 'rejectApplication'])->name('vehicle-applications.reject');
    Route::get('/appeal-reviews', function () { return view('officer.appeal-reviews'); })->name('appeal-reviews');
    Route::get('/issue-compound', function () { return view('officer.issue-compound'); })->name('issue-compound');
});

    // User only
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/my-vehicle', function () { return view('user.my-vehicle'); })->name('my-vehicle');
        Route::get('/my-compounds', function () { return view('user.my-compounds'); })->name('my-compounds');
        Route::get('/appeal', function () { return view('user.appeal'); })->name('appeal');
    });

        // Disable public registration
    Route::get('/register', function () {
        return redirect()->route('login');
    });

    Route::post('/register', function () {
        return redirect()->route('login');
    });

});