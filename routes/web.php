<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Officer\OfficerController;
 
Route::get('/', function () {
    return redirect()->route('dashboard');
});
 
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
 
        // Appeal Reviews
        Route::get('/appeal-reviews', [OfficerController::class, 'appealReviews'])->name('appeal-reviews');
        Route::post('/appeal/{appeal}', [OfficerController::class, 'updateAppeal'])->name('appeal.update');
 
        // Issue Compound
        Route::get('/issue-compound', [OfficerController::class, 'issueCompound'])->name('issue-compound');
        Route::post('/compound/lookup', [OfficerController::class, 'lookupPlate'])->name('compound.lookup');
        Route::post('/compound/clear', [OfficerController::class, 'clearLookup'])->name('compound.clear_lookup');
        Route::post('/compound/store', [OfficerController::class, 'storeCompound'])->name('compound.store');
        Route::post('/compound/update', [OfficerController::class, 'updateCompound'])->name('compound.update');
        Route::post('/compound/delete', [OfficerController::class, 'destroyCompound'])->name('compound.destroy');
    });
 
    // User only
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/my-vehicle', function () { return view('user.my-vehicle'); })->name('my-vehicle');
        Route::get('/my-compounds', function () { return view('user.my-compounds'); })->name('my-compounds');
        Route::get('/appeal', function () { return view('user.appeal'); })->name('appeal');
    });
 
    Route::get('/register', function () { return redirect()->route('login'); });
    Route::post('/register', function () { return redirect()->route('login'); });
 
});
 