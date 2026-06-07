<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Officer\OfficerController;
use App\Http\Controllers\User\MyVehicleController;
use App\Http\Controllers\User\MyCompoundController;
use App\Http\Controllers\User\AppealController;
use App\Http\Controllers\VehicleController;

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
        Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles');
        Route::patch('/vehicles/{vehicle}/approve', [VehicleController::class, 'approve'])->name('vehicles.approve');
        Route::patch('/vehicles/{vehicle}/reject', [VehicleController::class, 'reject'])->name('vehicles.reject');
        Route::patch('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
        Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
    });
 
    // Officer only
    Route::middleware(['role:officer'])->prefix('officer')->name('officer.')->group(function () {
    
        //Vehicle Applications
         Route::get('/vehicle-applications', [VehicleController::class, 'applications'])->name('vehicle-applications');
         Route::patch('/vehicle-applications/{vehicle}/approve', [VehicleController::class, 'approveApplication'])->name('vehicle-applications.approve');
         Route::patch('/vehicle-applications/{vehicle}/reject', [VehicleController::class, 'rejectApplication'])->name('vehicle-applications.reject');    
       
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
    Route::get('/my-vehicle', [MyVehicleController::class, 'index'])->name('my-vehicle');
    Route::post('/my-vehicle', [MyVehicleController::class, 'store'])->name('my-vehicle.store');
    Route::get('/my-compounds', [MyCompoundController::class, 'index'])->name('my-compounds');
  Route::get('/appeal', function () {
    return redirect()->route('user.my-compounds');
})->name('appeal');

Route::get('/appeal/{compound}', [AppealController::class, 'show'])->name('appeal.show');
Route::post('/appeal/{compound}', [AppealController::class, 'store'])->name('appeal.store');
});
    
 
    Route::get('/register', function () { return redirect()->route('login'); });
    Route::post('/register', function () { return redirect()->route('login'); });
 
});
 