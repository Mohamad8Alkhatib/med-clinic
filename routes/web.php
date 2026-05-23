<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Doctor\DoctorManagerController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

    Route::middleware('role:patient')->group(function () {
        Route::get('patient/doctors', [PatientController::class, 'index']);
        Route::get('appointments/create/{doctor}', [PatientController::class, 'createAppointment']);
        Route::post('appointments/create', [PatientController::class, 'store']);
        Route::get('patient/my-appointments', [PatientController::class, 'myAppointments']);
        Route::post('cancelAppointment/{appointment}', [PatientController::class, 'cancel']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('admin/dashboard', [AdminController::class, 'index']);
        Route::get('admin/reports', [AdminController::class, 'reportsIndex']);
        Route::get('admin/reports/appointments', [AdminController::class, 'appointmentReport']);
        Route::get('admin/reports/doctors', [AdminController::class, 'doctorsReport']);
        Route::get('admin/reports/cancellations', [AdminController::class, 'cancellationReport']);

        Route::get('/admin/specialties', [SpecialtyController::class, 'index']);
        Route::post('/admin/specialties', [SpecialtyController::class, 'store']);
        Route::delete('/admin/specialties/{id}', [SpecialtyController::class, 'destroy']);

        Route::get('admin/doctors', [DoctorController::class, 'index']);
        Route::post('admin/doctors', [DoctorController::class, 'store']);
        Route::put('admin/doctors/{id}', [DoctorController::class, 'update']);
        Route::delete('admin/doctors/{id}', [DoctorController::class, 'destroy']);
        Route::patch('admin/doctors/{id}/toggle-status', [DoctorController::class, 'toggleStatus']);
    });

    Route::middleware('role:doctor')->group(function () {
        Route::get('doctor/dashboard', [DoctorManagerController::class, 'index']);
        Route::put('doctor/appointments/{id}/status', [DoctorManagerController::class, 'updateStatus']);
    });
});


require __DIR__ . '/auth.php';
