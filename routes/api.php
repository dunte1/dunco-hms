<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes (no authentication required)
Route::post('/login', [ApiController::class, 'login'])->name('api.login');
Route::post('/register', [ApiController::class, 'register'])->name('api.register');

// Protected API routes (require authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // User management
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Patient management
    Route::get('/patients', [ApiController::class, 'getPatients'])->name('api.patients');
    Route::get('/patients/{patient}', [ApiController::class, 'getPatient'])->name('api.patient');
    Route::post('/patients', [ApiController::class, 'createPatient'])->name('api.patients.create');
    Route::put('/patients/{patient}', [ApiController::class, 'updatePatient'])->name('api.patients.update');
    Route::delete('/patients/{patient}', [ApiController::class, 'deletePatient'])->name('api.patients.delete');
    
    // Doctor management
    Route::get('/doctors', [ApiController::class, 'getDoctors'])->name('api.doctors');
    Route::get('/doctors/{doctor}', [ApiController::class, 'getDoctor'])->name('api.doctor');
    Route::post('/doctors', [ApiController::class, 'createDoctor'])->name('api.doctors.create');
    Route::put('/doctors/{doctor}', [ApiController::class, 'updateDoctor'])->name('api.doctors.update');
    Route::delete('/doctors/{doctor}', [ApiController::class, 'deleteDoctor'])->name('api.doctors.delete');
    
    // Appointment management
    Route::get('/appointments', [ApiController::class, 'getAppointments'])->name('api.appointments');
    Route::get('/appointments/{appointment}', [ApiController::class, 'getAppointment'])->name('api.appointment');
    Route::post('/appointments', [ApiController::class, 'createAppointment'])->name('api.appointments.create');
    Route::put('/appointments/{appointment}', [ApiController::class, 'updateAppointment'])->name('api.appointments.update');
    Route::delete('/appointments/{appointment}', [ApiController::class, 'deleteAppointment'])->name('api.appointments.delete');

    // Beds management
    Route::get('/beds', [ApiController::class, 'getBeds'])->name('api.beds');
    Route::post('/beds', [ApiController::class, 'createBed'])->name('api.beds.create');
    
    // Billing management
    Route::get('/invoices', [ApiController::class, 'getInvoices'])->name('api.invoices');
    Route::get('/invoices/{invoice}', [ApiController::class, 'getInvoice'])->name('api.invoice');
    Route::post('/invoices', [ApiController::class, 'createInvoice'])->name('api.invoices.create');
    Route::put('/invoices/{invoice}', [ApiController::class, 'updateInvoice'])->name('api.invoices.update');
    Route::delete('/invoices/{invoice}', [ApiController::class, 'deleteInvoice'])->name('api.invoices.delete');
    
    // Payment management
    Route::get('/payments', [ApiController::class, 'getPayments'])->name('api.payments');
    Route::post('/payments', [ApiController::class, 'createPayment'])->name('api.payments.create');
    
    // Token management
    Route::post('/tokens', [ApiController::class, 'generateToken'])->name('api.tokens.create');
    Route::get('/tokens', [ApiController::class, 'getTokens'])->name('api.tokens');
    Route::delete('/tokens/{token}', [ApiController::class, 'revokeToken'])->name('api.tokens.revoke');
    
    // Logout
    Route::post('/logout', [ApiController::class, 'logout'])->name('api.logout');
});

// M-Pesa Webhooks (no auth required - webhooks from Safaricom)
Route::post('/mpesa/callback', [\App\Http\Controllers\Hms\MpesaCallbackController::class, 'handleCallback'])
    ->name('mpesa.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// M-Pesa C2B Endpoints (Customer to Business payments)
Route::post('/mpesa/result', [\App\Http\Controllers\Hms\MpesaCallbackController::class, 'handleResult'])
    ->name('mpesa.result')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/mpesa/confirmation', [\App\Http\Controllers\Hms\MpesaCallbackController::class, 'handleConfirmation'])
    ->name('mpesa.confirmation')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/mpesa/validation', [\App\Http\Controllers\Hms\MpesaCallbackController::class, 'handleValidation'])
    ->name('mpesa.validation')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);