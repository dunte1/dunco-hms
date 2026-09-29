<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\V1\PatientController;
use App\Http\Controllers\Api\V1\PrescriptionController;
use App\Http\Controllers\Api\V1\LabRequestController;
use App\Http\Controllers\Api\V1\RadiologyRequestController;
use App\Http\Controllers\Api\V1\ImagingScheduleController;
use App\Http\Controllers\Api\V1\ModalityWorklistController as ApiModalityWorklistController;

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
    
    // Legacy API routes (monolithic controller)
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

    // ── V1 Resource API ─────────────────────────────────────────────────
    Route::prefix('v1')->group(function () {
        // Patients
        Route::get('/patients', [PatientController::class, 'index'])
            ->middleware('permission:view patients')
            ->name('v1.patients.index');
        Route::post('/patients', [PatientController::class, 'store'])
            ->middleware('permission:add patients')
            ->name('v1.patients.store');
        Route::get('/patients/{patient}', [PatientController::class, 'show'])
            ->middleware('permission:view patients')
            ->name('v1.patients.show');
        Route::put('/patients/{patient}', [PatientController::class, 'update'])
            ->middleware('permission:edit patients')
            ->name('v1.patients.update');
        Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])
            ->middleware('permission:delete patients')
            ->name('v1.patients.destroy');

        // Prescriptions
        Route::get('/prescriptions', [PrescriptionController::class, 'index'])
            ->middleware('permission:view prescriptions')
            ->name('v1.prescriptions.index');
        Route::post('/prescriptions', [PrescriptionController::class, 'store'])
            ->middleware('permission:create prescriptions')
            ->name('v1.prescriptions.store');
        Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])
            ->middleware('permission:view prescriptions')
            ->name('v1.prescriptions.show');
        Route::put('/prescriptions/{prescription}', [PrescriptionController::class, 'update'])
            ->middleware('permission:edit prescriptions')
            ->name('v1.prescriptions.update');

        // Lab Requests
        Route::get('/lab-requests', [LabRequestController::class, 'index'])
            ->middleware('permission:view patients')
            ->name('v1.lab-requests.index');
        Route::post('/lab-requests', [LabRequestController::class, 'store'])
            ->middleware('permission:add patients')
            ->name('v1.lab-requests.store');
        Route::get('/lab-requests/{labRequest}', [LabRequestController::class, 'show'])
            ->middleware('permission:view patients')
            ->name('v1.lab-requests.show');
        Route::put('/lab-requests/{labRequest}', [LabRequestController::class, 'update'])
            ->middleware('permission:edit patients')
            ->name('v1.lab-requests.update');

        // Lab Specimens
        Route::post('/lab/specimens', [\App\Http\Controllers\Hms\LabSpecimenController::class, 'store'])
            ->middleware('permission:add patients')
            ->name('v1.lab-specimens.store');

        // Lab Worklists
        Route::post('/lab/worklists', [\App\Http\Controllers\Hms\LabWorklistController::class, 'store'])
            ->middleware('permission:add patients')
            ->name('v1.lab-worklists.store');

        // Blood Bank
        Route::post('/blood-bank/units', [\App\Http\Controllers\Api\V1\BloodBankApiController::class, 'storeUnit'])
            ->middleware('permission:add patients')
            ->name('v1.blood-bank.units.store');
        Route::post('/blood-bank/transfusions', [\App\Http\Controllers\Api\V1\BloodBankApiController::class, 'storeTransfusion'])
            ->middleware('permission:add patients')
            ->name('v1.blood-bank.transfusions.store');

        // Radiology Requests
        Route::get('/radiology-requests', [RadiologyRequestController::class, 'index'])
            ->middleware('permission:view patients')
            ->name('v1.radiology-requests.index');
        Route::post('/radiology-requests', [RadiologyRequestController::class, 'store'])
            ->middleware('permission:add patients')
            ->name('v1.radiology-requests.store');
        Route::get('/radiology-requests/{radiologyRequest}', [RadiologyRequestController::class, 'show'])
            ->middleware('permission:view patients')
            ->name('v1.radiology-requests.show');
        Route::put('/radiology-requests/{radiologyRequest}', [RadiologyRequestController::class, 'update'])
            ->middleware('permission:edit patients')
            ->name('v1.radiology-requests.update');

        // Radiology Scheduling & Worklist (G028-G030)
        Route::post('/radiology/schedules', [ImagingScheduleController::class, 'store'])
            ->middleware('permission:add patients')
            ->name('v1.radiology-schedules.store');
        Route::get('/radiology/schedules', [ImagingScheduleController::class, 'index'])
            ->middleware('permission:view patients')
            ->name('v1.radiology-schedules.index');
        Route::get('/radiology/worklist', [ApiModalityWorklistController::class, 'index'])
            ->middleware('permission:view patients')
            ->name('v1.radiology-worklist.index');

        // Triage
        Route::post('/triage', [\App\Http\Controllers\Api\V1\TriageController::class, 'store'])
            ->middleware('permission:add patients')
            ->name('v1.triage.store');
        Route::get('/triage/queue', [\App\Http\Controllers\Api\V1\TriageController::class, 'queue'])
            ->middleware('permission:view patients')
            ->name('v1.triage.queue');
    });
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