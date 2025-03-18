<?php

use App\Http\Controllers\BookAppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/book-appointment', [BookAppointmentController::class, 'bookappointmentpage'])->name('book-appointment'); 

Route::post('/bookMeeting', [BookAppointmentController::class, 'store'])->name('bookMeeting');

Route::get('/api/available-slots', [BookAppointmentController::class, 'getAvailableSlots']);

Route::get('/quality-pending', [BookAppointmentController::class, 'qualitypending'])->name('qualitypending');
 

Route::post('/quality-approve/{appointment}', [BookAppointmentController::class, 'qualityApprove'])->name('qualityApprove');
Route::delete('/quality-reject/{appointment}', [BookAppointmentController::class, 'qualityReject'])->name('qualityReject');