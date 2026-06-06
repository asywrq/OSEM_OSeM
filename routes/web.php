<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Officer\OfficerController;

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
        Route::get('/vehicle-applications', function () { return view('officer.vehicle-applications'); })->name('vehicle-applications');

        // Appeal Reviews
        Route::get('/appeal-reviews', [OfficerController::class, 'appealReviews'])->name('appeal-reviews');
        Route::patch('/appeal/{appeal}', [OfficerController::class, 'updateAppeal'])->name('appeal.update');

        // Issue Compound
        Route::get('/issue-compound', [OfficerController::class, 'issueCompound'])->name('issue-compound');
        Route::post('/compound/lookup', [OfficerController::class, 'lookupPlate'])->name('compound.lookup');
        Route::post('/compound/store', [OfficerController::class, 'storeCompound'])->name('compound.store');
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