<?php

use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminDashboardController;

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');

Route::get('/admin/users', [AdminDashboardController::class, 'users'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users');

Route::get('/admin/doctors', [AdminDashboardController::class, 'doctors'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.doctors');

Route::patch('/admin/doctors/{doctor}/toggle-verification', [AdminDashboardController::class, 'toggleDoctorVerification'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.doctors.toggle-verification');

Route::get('/admin/appointments', [AdminDashboardController::class, 'appointments'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.appointments');

Route::patch('/admin/appointments/{appointment}/confirm', [AdminDashboardController::class, 'confirmAppointment'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.appointments.confirm');

Route::patch('/admin/appointments/{appointment}/complete', [AdminDashboardController::class, 'completeAppointment'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.appointments.complete');

Route::patch('/admin/appointments/{appointment}/cancel', [AdminDashboardController::class, 'cancelAppointment'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.appointments.cancel');

Route::patch('/admin/users/{user}/role', [AdminDashboardController::class, 'updateUserRole'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users.update-role');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

Route::get('/doctor/schedule', [DoctorDashboardController::class, 'schedule'])
    ->middleware(['auth', 'role:doctor,admin'])
    ->name('doctor.schedule');

Route::get('/doctor/appointments', [DoctorDashboardController::class, 'appointments'])
    ->middleware(['auth', 'role:doctor,admin'])
    ->name('doctor.appointments');

Route::patch('/doctor/appointments/{appointment}/confirm', [DoctorDashboardController::class, 'confirmAppointment'])
    ->middleware(['auth', 'role:doctor,admin'])
    ->name('doctor.appointments.confirm');

Route::patch('/doctor/appointments/{appointment}/complete', [DoctorDashboardController::class, 'completeAppointment'])
    ->middleware(['auth', 'role:doctor,admin'])
    ->name('doctor.appointments.complete');

Route::get('/my-appointments', [PatientDashboardController::class, 'appointments'])
    ->middleware(['auth', 'role:patient,admin'])
    ->name('patient.appointments');

Route::post('/appointments', [AppointmentController::class, 'store'])
    ->middleware('auth')
    ->name('appointments.store');

Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
    ->middleware(['auth', 'role:patient,admin'])
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
