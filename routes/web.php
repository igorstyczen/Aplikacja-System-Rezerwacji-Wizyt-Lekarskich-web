<?php

use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

Route::get('/doctor/schedule', [DoctorDashboardController::class, 'schedule'])
    ->middleware('auth')
    ->name('doctor.schedule');

Route::get('/doctor/appointments', [DoctorDashboardController::class, 'appointments'])
->middleware('auth')
->name('doctor.appointments');

Route::get('/my-appointments', [PatientDashboardController::class, 'appointments'])
    ->middleware('auth')
    ->name('patient.appointments');

Route::post('/appointments', [AppointmentController::class, 'store'])
    ->middleware('auth')
    ->name('appointments.store');

Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
    ->middleware('auth')
    ->name('appointments.cancel');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
