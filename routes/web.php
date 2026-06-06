<?php

use Illuminate\Support\Facades\Route;

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
        Route::get('/vehicles', function () { return view('admin.vehicles'); })->name('vehicles');
    });

    // Officer only
    Route::middleware(['role:officer'])->prefix('officer')->name('officer.')->group(function () {
        Route::get('/vehicle-applications', function () { return view('officer.vehicle-applications'); })->name('vehicle-applications');
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