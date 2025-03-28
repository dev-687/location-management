<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StateCityPincodeController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/location/', [StateCityPincodeController::class, 'index'])->name('location.index');
    Route::get('/location/create', [StateCityPincodeController::class, 'create'])->name('location.create');
    Route::get('/get-cities/{stateId}/{location_id?}', [StateCityPincodeController::class, 'getCities']);
    Route::post('/location/store', [StateCityPincodeController::class, 'store'])->name('location.store');
    Route::put('/location/update', [StateCityPincodeController::class, 'update'])->name('location.update');
});

require __DIR__.'/auth.php';
