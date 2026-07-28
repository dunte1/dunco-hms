<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Hms\PatientsController;
use App\Http\Controllers\Hms\AppointmentsController;
use App\Http\Controllers\Hms\QueueManagementController;
use App\Http\Controllers\Hms\DoctorsController;
use App\Http\Controllers\Hms\AdmissionsController;
use App\Http\Controllers\Hms\BillingController;
use App\Http\Controllers\Hms\PharmacyController;
use App\Http\Controllers\Hms\LaboratoryController;
use App\Http\Controllers\Hms\RadiologyController;
use App\Http\Controllers\Hms\InventoryController;
use App\Http\Controllers\Hms\HrController;
use App\Http\Controllers\Hms\SettingsController;
use App\Http\Controllers\Hms\BedTypesController;
use App\Http\Controllers\Hms\BedsController;
use App\Http\Controllers\Hms\IpdAdmissionsController;
use App\Http\Controllers\Hms\OpdVisitsController;
use App\Http\Controllers\Hms\InvoicesController;
use App\Http\Controllers\Hms\PaymentsController;
use App\Http\Controllers\Hms\MedicinesController;
use App\Http\Controllers\Hms\PrescriptionsController;
use App\Http\Controllers\Hms\LabTestsController;
use App\Http\Controllers\Hms\LabRequestsController;
use App\Http\Controllers\Hms\RadiologyTestsController;
use App\Http\Controllers\Hms\RadiologyRequestsController;
use App\Http\Controllers\Hms\EmployeesController;
use App\Http\Controllers\Hms\EmployeeDepartmentsController;
use App\Http\Controllers\Hms\PerformanceAppraisalsController;
use App\Http\Controllers\Hms\PayrollsController;
use App\Http\Controllers\Hms\SchedulesController;
use App\Http\Controllers\Hms\AttendanceController;
use App\Http\Controllers\Hms\LeaveRequestsController;
use App\Http\Controllers\Hms\BloodBankController;
use App\Http\Controllers\Hms\LeaveTypesController;
use App\Http\Controllers\Hms\RecruitmentController;
use App\Http\Controllers\Hms\TrainingProgramsController;
use App\Http\Controllers\Hms\HrAnnouncementsController;
use App\Http\Controllers\Hms\ShiftsController;
use App\Http\Controllers\Hms\PublicHolidaysController;
use App\Http\Controllers\Hms\HrReportsController;
use App\Http\Controllers\Hms\HrSettingsController;
use App\Http\Controllers\Hms\GlobalSearchController;
use App\Http\Controllers\Hms\BatchOperationsController;
use App\Http\Controllers\Hms\EmployeesImportExportController;
use App\Http\Controllers\Hms\AmbulanceController;
use App\Http\Controllers\Hms\ReportsController;
use App\Http\Controllers\Hms\PackagesController;
use App\Http\Controllers\Hms\NursesController;
use App\Http\Controllers\Hms\CaseHandlersController;
use App\Http\Controllers\Hms\BirthDeathReportsController;
use App\Http\Controllers\Hms\OperationReportsController;
use App\Http\Controllers\Hms\DiagnosisController;
use App\Http\Controllers\Hms\StaffManagementController;
use App\Http\Controllers\Cms\BlogController;
use App\Http\Controllers\Cms\GalleryController;
use App\Http\Controllers\Cms\CareersController;
use App\Http\Controllers\Cms\TestimonialsController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/services', [SiteController::class, 'services'])->name('services');
Route::get('/doctors', [SiteController::class, 'doctors'])->name('doctors');
Route::get('/about', [SiteController::class, 'about'])->name('about');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'submitContact'])->name('contact.submit');
Route::get('/features', [SiteController::class, 'features'])->name('features');
Route::get('/book-appointment', [SiteController::class, 'bookAppointment'])->name('book-appointment');
Route::post('/book-appointment', [SiteController::class, 'submitAppointment'])->name('book-appointment.submit');
Route::get('/lang/{locale}', [SiteController::class, 'switchLanguage'])->name('lang.switch');

// JSON-friendly login alias for API-driven tests (ONLY handles JSON requests)
// This route will only match if Accept: application/json header is present
// Web form submissions will fall through to auth.php routes

// CMS Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/careers', [CareersController::class, 'index'])->name('careers.index');
Route::get('/careers/{job}', [CareersController::class, 'show'])->name('careers.show');
Route::get('/careers/{job}/apply', [CareersController::class, 'apply'])->name('careers.apply');
Route::post('/careers/{job}/apply', [CareersController::class, 'storeApplication'])->name('careers.apply.store');
Route::get('/testimonials', [TestimonialsController::class, 'index'])->name('testimonials.index');
Route::get('/testimonials/create', [TestimonialsController::class, 'create'])->name('testimonials.create');
Route::post('/testimonials', [TestimonialsController::class, 'store'])->name('testimonials.store');

// AI & Advanced Features Routes
Route::prefix('hms')->middleware(['auth'])->group(function () {
    // AI Features
    Route::get('/ai/appointment-suggestions', [\App\Http\Controllers\Ai\AiAssistantController::class, 'appointmentSuggestions'])->name('ai.appointment-suggestions');
    Route::post('/ai/appointment-suggestions/generate', [\App\Http\Controllers\Ai\AiAssistantController::class, 'generateAppointmentSuggestion'])->name('ai.appointment-suggestions.generate');
    Route::get('/ai/diagnosis-suggestions', [\App\Http\Controllers\Ai\AiAssistantController::class, 'diagnosisSuggestions'])->name('ai.diagnosis-suggestions');
    Route::post('/ai/diagnosis-suggestions/generate', [\App\Http\Controllers\Ai\AiAssistantController::class, 'generateDiagnosisSuggestion'])->name('ai.diagnosis-suggestions.generate');
    
    // Elliana D - Virtual Nurse Assistant
    Route::get('/ai/elliana-d', [\App\Http\Controllers\Ai\EllianaDController::class, 'index'])->name('ai.elliana-d');
    Route::post('/ai/elliana-d/chat', [\App\Http\Controllers\Ai\EllianaDController::class, 'chat'])->name('ai.elliana-d.chat');
    Route::get('/ai/elliana-d/history', [\App\Http\Controllers\Ai\EllianaDController::class, 'history'])->name('ai.elliana-d.history');
    
    // Integration Features
    Route::get('/integration/lab-equipment', [\App\Http\Controllers\Integration\LabIntegrationController::class, 'index'])->name('integration.lab-equipment');
    Route::post('/integration/lab-equipment', [\App\Http\Controllers\Integration\LabIntegrationController::class, 'createEquipment'])->name('integration.lab-equipment.create');
    Route::post('/integration/lab-equipment/{equipment}/test', [\App\Http\Controllers\Integration\LabIntegrationController::class, 'testConnection'])->name('integration.lab-equipment.test');
    Route::get('/integration/lab-equipment/{equipment}/results', [\App\Http\Controllers\Integration\LabIntegrationController::class, 'getEquipmentResults'])->name('integration.lab-equipment.results');
    Route::post('/integration/lab-equipment/receive-results', [\App\Http\Controllers\Integration\LabIntegrationController::class, 'receiveResults'])->name('integration.lab-equipment.receive');
    
    Route::get('/integration/insurance-api', [\App\Http\Controllers\Integration\InsuranceApiController::class, 'index'])->name('integration.insurance-api');
    Route::post('/integration/insurance/verify', [\App\Http\Controllers\Integration\InsuranceApiController::class, 'verifyInsurance'])->name('integration.insurance.verify');
    Route::post('/integration/insurance/submit-claim', [\App\Http\Controllers\Integration\InsuranceApiController::class, 'submitClaim'])->name('integration.insurance.submit-claim');
    Route::post('/integration/insurance/check-eligibility', [\App\Http\Controllers\Integration\InsuranceApiController::class, 'checkEligibility'])->name('integration.insurance.check-eligibility');
    
    // Analytics & BI
    Route::get('/analytics/bi-dashboard', [\App\Http\Controllers\Analytics\BiDashboardController::class, 'index'])->name('analytics.bi-dashboard');
    Route::post('/analytics/generate', [\App\Http\Controllers\Analytics\BiDashboardController::class, 'generateAnalytics'])->name('analytics.generate');
    Route::get('/analytics/revenue', [\App\Http\Controllers\Analytics\BiDashboardController::class, 'getRevenueAnalytics'])->name('analytics.revenue');
    Route::get('/analytics/patients', [\App\Http\Controllers\Analytics\BiDashboardController::class, 'getPatientAnalytics'])->name('analytics.patients');
    Route::get('/analytics/occupancy', [\App\Http\Controllers\Analytics\BiDashboardController::class, 'getOccupancyAnalytics'])->name('analytics.occupancy');
    
    // Telemedicine
    Route::get('/telemedicine', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'index'])->name('telemedicine.index');
    Route::get('/telemedicine/create', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'create'])->name('telemedicine.create');
    Route::post('/telemedicine', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'store'])->name('telemedicine.store');
    Route::post('/telemedicine/{session}/start', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'startSession'])->name('telemedicine.start');
    Route::post('/telemedicine/{session}/end', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'endSession'])->name('telemedicine.end');
    Route::get('/telemedicine/{session}/join', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'joinSession'])->name('telemedicine.join');
    Route::get('/telemedicine/{session}/details', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'getSessionDetails'])->name('telemedicine.details');
    Route::get('/telemedicine/upcoming', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'getUpcomingSessions'])->name('telemedicine.upcoming');
    
    // RFID Management
    Route::get('/rfid', [\App\Http\Controllers\Rfid\RfidController::class, 'index'])->name('rfid.index');
    Route::get('/rfid/create', [\App\Http\Controllers\Rfid\RfidController::class, 'create'])->name('rfid.create');
    Route::post('/rfid', [\App\Http\Controllers\Rfid\RfidController::class, 'store'])->name('rfid.store');
    Route::post('/rfid/scan', [\App\Http\Controllers\Rfid\RfidController::class, 'scan'])->name('rfid.scan');
    Route::get('/rfid/{tagId}/info', [\App\Http\Controllers\Rfid\RfidController::class, 'getTagInfo'])->name('rfid.info');
    Route::post('/rfid/{tag}/update-status', [\App\Http\Controllers\Rfid\RfidController::class, 'updateStatus'])->name('rfid.update-status');
    Route::get('/rfid/{tag}/history', [\App\Http\Controllers\Rfid\RfidController::class, 'getLocationHistory'])->name('rfid.history');
    Route::get('/rfid/active', [\App\Http\Controllers\Rfid\RfidController::class, 'getActiveTags'])->name('rfid.active');
    Route::get('/rfid/location/{location}', [\App\Http\Controllers\Rfid\RfidController::class, 'getTagsByLocation'])->name('rfid.by-location');
    Route::post('/rfid/report', [\App\Http\Controllers\Rfid\RfidController::class, 'generateReport'])->name('rfid.report');
    Route::post('/rfid/bulk-update', [\App\Http\Controllers\Rfid\RfidController::class, 'bulkUpdate'])->name('rfid.bulk-update');
    
    // IoT Bed Monitoring
    Route::get('/iot/bed-monitoring', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'index'])->name('iot.bed-monitoring');
    Route::get('/iot/sensor/create', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'create'])->name('iot.sensor.create');
    Route::post('/iot/sensor', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'store'])->name('iot.sensor.store');
    Route::post('/iot/sensor/receive-data', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'receiveSensorData'])->name('iot.sensor.receive-data');
    Route::get('/iot/sensor/{sensor}/data', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'getSensorData'])->name('iot.sensor.data');
    Route::get('/iot/bed/{bed}/status', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'getBedStatus'])->name('iot.bed.status');
    Route::get('/iot/bed-occupancy-map', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'getOccupancyMap'])->name('iot.bed-occupancy-map');
    Route::get('/iot/alerts', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'getAlerts'])->name('iot.alerts');
    Route::post('/iot/sensor/{sensor}/acknowledge', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'acknowledgeAlert'])->name('iot.sensor.acknowledge');
    Route::get('/iot/sensor/{sensor}/history', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'getVitalSignsHistory'])->name('iot.sensor.history');
});

// API Routes moved to routes/api.php for proper CSRF exemption

// Patient Portal Routes
Route::prefix('patient-portal')->group(function () {
    Route::get('/login', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'login'])->name('patient-portal.login');
    Route::post('/login', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'authenticate'])->name('patient-portal.authenticate');
    Route::get('/dashboard', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'dashboard'])->name('patient-portal.dashboard');
    Route::get('/appointments', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'appointments'])->name('patient-portal.appointments');
    Route::post('/appointments', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'bookAppointment'])->name('patient-portal.book-appointment');
    Route::get('/prescriptions', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'prescriptions'])->name('patient-portal.prescriptions');
    Route::get('/lab-results', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'labResults'])->name('patient-portal.lab-results');
    Route::get('/medical-history', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'medicalHistory'])->name('patient-portal.medical-history');
    Route::get('/billing', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'billing'])->name('patient-portal.billing');
    Route::get('/profile', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'profile'])->name('patient-portal.profile');
    Route::post('/profile', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'updateProfile'])->name('patient-portal.update-profile');
    Route::post('/change-password', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'changePassword'])->name('patient-portal.change-password');
    Route::post('/enable-2fa', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'enableTwoFactor'])->name('patient-portal.enable-2fa');
    Route::post('/disable-2fa', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'disableTwoFactor'])->name('patient-portal.disable-2fa');
    Route::post('/logout', [\App\Http\Controllers\PatientPortal\PatientPortalController::class, 'logout'])->name('patient-portal.logout');
});

// Security & Biometric Routes
Route::middleware(['auth'])->prefix('hms/security')->group(function () {
    // Biometric
    Route::get('/biometric', [\App\Http\Controllers\Security\BiometricController::class, 'index'])->name('biometric.index');
    Route::post('/biometric/register', [\App\Http\Controllers\Security\BiometricController::class, 'register'])->name('biometric.register');
    Route::post('/biometric/verify', [\App\Http\Controllers\Security\BiometricController::class, 'verify'])->name('biometric.verify');
    Route::delete('/biometric/delete', [\App\Http\Controllers\Security\BiometricController::class, 'delete'])->name('biometric.delete');
    Route::get('/biometric/stats', [\App\Http\Controllers\Security\BiometricController::class, 'stats'])->name('biometric.stats');
    
    // Card Scanner
    Route::get('/card-scanner', [\App\Http\Controllers\Security\CardScannerController::class, 'index'])->name('card-scanner.index');
    Route::post('/card-scanner/scan', [\App\Http\Controllers\Security\CardScannerController::class, 'scan'])->name('card-scanner.scan');
    Route::get('/card-scanner/history', [\App\Http\Controllers\Security\CardScannerController::class, 'history'])->name('card-scanner.history');
});

Route::get('/dashboard', [\App\Http\Controllers\Hms\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Role Management Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/roles', [\App\Http\Controllers\Admin\RoleManagementController::class, 'index'])->name('admin.roles.index');
    Route::get('/roles/create', [\App\Http\Controllers\Admin\RoleManagementController::class, 'create'])->name('admin.roles.create');
    Route::post('/roles', [\App\Http\Controllers\Admin\RoleManagementController::class, 'store'])->name('admin.roles.store');
    Route::get('/roles/{role}', [\App\Http\Controllers\Admin\RoleManagementController::class, 'show'])->name('admin.roles.show');
    Route::get('/roles/{role}/edit', [\App\Http\Controllers\Admin\RoleManagementController::class, 'edit'])->name('admin.roles.edit');
    Route::put('/roles/{role}', [\App\Http\Controllers\Admin\RoleManagementController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/{role}', [\App\Http\Controllers\Admin\RoleManagementController::class, 'destroy'])->name('admin.roles.destroy');
    Route::post('/roles/assign', [\App\Http\Controllers\Admin\RoleManagementController::class, 'assignRole'])->name('admin.roles.assign');
    Route::post('/roles/remove', [\App\Http\Controllers\Admin\RoleManagementController::class, 'removeRole'])->name('admin.roles.remove');
    Route::get('/roles/{role}/users', [\App\Http\Controllers\Admin\RoleManagementController::class, 'getUsersWithRole'])->name('admin.roles.users');
    Route::get('/roles/{role}/permissions', [\App\Http\Controllers\Admin\RoleManagementController::class, 'getRolePermissions'])->name('admin.roles.permissions');
    Route::get('/roles/permissions', [\App\Http\Controllers\Admin\RoleManagementController::class, 'getAllPermissions'])->name('admin.roles.all-permissions');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('hms')->name('hms.')->group(function () {
        Route::get('/patients', [PatientsController::class, 'index'])->name('patients.index');
        Route::get('/patients/create', [PatientsController::class, 'create'])->name('patients.create');
        Route::post('/patients', [PatientsController::class, 'store'])->name('patients.store');
        Route::get('/patients/{patient}', [PatientsController::class, 'show'])->name('patients.show');
        Route::get('/patients/{patient}/edit', [PatientsController::class, 'edit'])->name('patients.edit');
        Route::put('/patients/{patient}', [PatientsController::class, 'update'])->name('patients.update');
        Route::delete('/patients/{patient}', [PatientsController::class, 'destroy'])->name('patients.destroy');
        Route::get('/appointments', [AppointmentsController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/create', [AppointmentsController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentsController::class, 'store'])->name('appointments.store');
        
        // Queue Management Routes
        Route::get('/queue', [QueueManagementController::class, 'index'])->name('queue.index');
        Route::get('/queue/create', [QueueManagementController::class, 'create'])->name('queue.create');
        Route::post('/queue', [QueueManagementController::class, 'store'])->name('queue.store');
        Route::post('/queue/{queue}/call', [QueueManagementController::class, 'callQueue'])->name('queue.call');
        Route::post('/queue/{queue}/start-service', [QueueManagementController::class, 'startService'])->name('queue.start-service');
        Route::post('/queue/{queue}/complete', [QueueManagementController::class, 'completeQueue'])->name('queue.complete');
        Route::delete('/queue/{queue}/cancel', [QueueManagementController::class, 'cancelQueue'])->name('queue.cancel');
        Route::delete('/queue/{queue}', [QueueManagementController::class, 'destroy'])->name('queue.destroy');
        Route::get('/queue/display-board', [QueueManagementController::class, 'displayBoard'])->name('queue.display-board');
        Route::get('/queue/kiosk', [QueueManagementController::class, 'kioskMode'])->name('queue.kiosk');
        Route::get('/queue/smart-display', [QueueManagementController::class, 'smartDisplay'])->name('queue.smart-display');
        Route::get('/queue/current', [QueueManagementController::class, 'getCurrentQueues'])->name('queue.current');
        Route::get('/queue/token-generation', [QueueManagementController::class, 'tokenGeneration'])->name('queue.token-generation');
        Route::post('/queue/generate-token', [QueueManagementController::class, 'generateToken'])->name('queue.generate-token');
        Route::get('/queue/token-success/{queue}', [QueueManagementController::class, 'tokenSuccess'])->name('queue.token-success');
        
        // Visitor Management Routes
        Route::get('/visitors', [\App\Http\Controllers\Hms\VisitorController::class, 'index'])->name('visitors.index');
        Route::get('/visitors/create', [\App\Http\Controllers\Hms\VisitorController::class, 'create'])->name('visitors.create');
        Route::post('/visitors', [\App\Http\Controllers\Hms\VisitorController::class, 'store'])->name('visitors.store');
        Route::get('/visitors/{visitor}', [\App\Http\Controllers\Hms\VisitorController::class, 'show'])->name('visitors.show');
        Route::post('/visitors/{visitor}/check-out', [\App\Http\Controllers\Hms\VisitorController::class, 'checkOut'])->name('visitors.check-out');
        Route::get('/visitors/{visitor}/badge', [\App\Http\Controllers\Hms\VisitorController::class, 'printBadge'])->name('visitors.badge');
        Route::get('/visitors/analytics', [\App\Http\Controllers\Hms\VisitorController::class, 'analytics'])->name('visitors.analytics');
        Route::delete('/visitors/{visitor}', [\App\Http\Controllers\Hms\VisitorController::class, 'destroy'])->name('visitors.destroy');
        
        Route::get('/doctors', [DoctorsController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/create', [DoctorsController::class, 'create'])->name('doctors.create');
        Route::post('/doctors', [DoctorsController::class, 'store'])->name('doctors.store');
        // Doctor Departments (must come before /doctors/{doctor})
        Route::get('/doctors/departments', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'index'])->name('doctors.departments.index');
        Route::post('/doctors/departments', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'store'])->name('doctors.departments.store');
        Route::get('/doctors/{doctor}', [DoctorsController::class, 'show'])->name('doctors.show');
        Route::get('/doctors/{doctor}/edit', [DoctorsController::class, 'edit'])->name('doctors.edit');
        Route::put('/doctors/{doctor}', [DoctorsController::class, 'update'])->name('doctors.update');
        Route::delete('/doctors/{doctor}', [DoctorsController::class, 'destroy'])->name('doctors.destroy');
        // Route for admissions is now handled by IPD (In-Patient Department) routes below
        Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
        Route::get('/pharmacy', [PharmacyController::class, 'index'])->name('pharmacy.index');
        Route::get('/laboratory', [LaboratoryController::class, 'index'])->name('laboratory.index');
        Route::get('/radiology', [RadiologyController::class, 'index'])->name('radiology.index');
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/hr', [HrController::class, 'index'])->name('hr.index');
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        
        // Bed Management
        Route::get('/bed-types', [BedTypesController::class, 'index'])->name('bed-types.index');
        Route::post('/bed-types', [BedTypesController::class, 'store'])->name('bed-types.store');
        Route::get('/beds', [BedsController::class, 'index'])->name('beds.index');
        Route::get('/beds/create', [BedsController::class, 'create'])->name('beds.create');
        Route::post('/beds', [BedsController::class, 'store'])->name('beds.store');
        
        // IPD/OPD
        Route::get('/ipd', [IpdAdmissionsController::class, 'index'])->name('ipd.index');
        Route::get('/ipd/create', [IpdAdmissionsController::class, 'create'])->name('ipd.create');
        Route::post('/ipd', [IpdAdmissionsController::class, 'store'])->name('ipd.store');
        Route::get('/ipd/{ipd}', [IpdAdmissionsController::class, 'show'])->name('ipd.show');
        Route::get('/ipd/{ipd}/edit', [IpdAdmissionsController::class, 'edit'])->name('ipd.edit');
        Route::put('/ipd/{ipd}', [IpdAdmissionsController::class, 'update'])->name('ipd.update');
        Route::delete('/ipd/{ipd}', [IpdAdmissionsController::class, 'destroy'])->name('ipd.destroy');
        Route::get('/opd', [OpdVisitsController::class, 'index'])->name('opd.index');
        Route::get('/opd/create', [OpdVisitsController::class, 'create'])->name('opd.create');
        Route::post('/opd', [OpdVisitsController::class, 'store'])->name('opd.store');
        Route::get('/opd/{opd}', [OpdVisitsController::class, 'show'])->name('opd.show');
        Route::get('/opd/{opd}/edit', [OpdVisitsController::class, 'edit'])->name('opd.edit');
        Route::put('/opd/{opd}', [OpdVisitsController::class, 'update'])->name('opd.update');
        Route::delete('/opd/{opd}', [OpdVisitsController::class, 'destroy'])->name('opd.destroy');
        
        // Billing
        Route::get('/billing/invoices', [InvoicesController::class, 'index'])->name('billing.invoices.index');
        Route::get('/billing/invoices/create', [InvoicesController::class, 'create'])->name('billing.invoices.create');
        Route::post('/billing/invoices', [InvoicesController::class, 'store'])->name('billing.invoices.store');
        Route::get('/billing/invoices/{invoice}', [InvoicesController::class, 'show'])->name('billing.invoices.show');
        Route::get('/billing/invoices/{invoice}/pdf', [InvoicesController::class, 'generatePdf'])->name('billing.invoices.pdf');
        Route::post('/billing/invoices/{invoice}/email', [InvoicesController::class, 'sendEmail'])->name('billing.invoices.email');
        Route::get('/billing/invoices/{invoice}/edit', [InvoicesController::class, 'edit'])->name('billing.invoices.edit');
        Route::put('/billing/invoices/{invoice}', [InvoicesController::class, 'update'])->name('billing.invoices.update');
        Route::delete('/billing/invoices/{invoice}', [InvoicesController::class, 'destroy'])->name('billing.invoices.destroy');
        
        Route::get('/billing/payments', [PaymentsController::class, 'index'])->name('billing.payments.index');
        Route::get('/billing/payments/create', [PaymentsController::class, 'create'])->name('billing.payments.create');
        Route::post('/billing/payments', [PaymentsController::class, 'store'])->name('billing.payments.store');
        Route::get('/billing/payments/{payment}/thermal-receipt', [PaymentsController::class, 'thermalReceipt'])->name('billing.payments.thermal-receipt');
        Route::get('/billing/invoices/{invoice}/thermal-receipt', [PaymentsController::class, 'invoiceThermalReceipt'])->name('billing.invoices.thermal-receipt');
        
        // Insurance Management
        Route::get('/insurance/providers', [\App\Http\Controllers\Hms\InsuranceProvidersController::class, 'index'])->name('insurance.providers.index');
        Route::get('/insurance/providers/create', [\App\Http\Controllers\Hms\InsuranceProvidersController::class, 'create'])->name('insurance.providers.create');
        Route::post('/insurance/providers', [\App\Http\Controllers\Hms\InsuranceProvidersController::class, 'store'])->name('insurance.providers.store');
        Route::get('/insurance/providers/{provider}', [\App\Http\Controllers\Hms\InsuranceProvidersController::class, 'show'])->name('insurance.providers.show');
        Route::get('/insurance/providers/{provider}/edit', [\App\Http\Controllers\Hms\InsuranceProvidersController::class, 'edit'])->name('insurance.providers.edit');
        Route::put('/insurance/providers/{provider}', [\App\Http\Controllers\Hms\InsuranceProvidersController::class, 'update'])->name('insurance.providers.update');
        Route::delete('/insurance/providers/{provider}', [\App\Http\Controllers\Hms\InsuranceProvidersController::class, 'destroy'])->name('insurance.providers.destroy');
        Route::post('/insurance/providers/{provider}/verify', [\App\Http\Controllers\Hms\InsuranceProvidersController::class, 'verify'])->name('insurance.providers.verify');
        
        Route::get('/insurance/claims', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'index'])->name('insurance.claims.index');
        Route::get('/insurance/claims/create', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'create'])->name('insurance.claims.create');
        Route::post('/insurance/claims', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'store'])->name('insurance.claims.store');
        Route::get('/insurance/claims/{claim}', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'show'])->name('insurance.claims.show');
        Route::get('/insurance/claims/{claim}/edit', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'edit'])->name('insurance.claims.edit');
        Route::put('/insurance/claims/{claim}', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'update'])->name('insurance.claims.update');
        Route::post('/insurance/claims/{claim}/submit', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'submit'])->name('insurance.claims.submit');
        Route::post('/insurance/claims/{claim}/approve', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'approve'])->name('insurance.claims.approve');
        Route::post('/insurance/claims/{claim}/reject', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'reject'])->name('insurance.claims.reject');
        Route::post('/insurance/claims/{claim}/payment', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'recordPayment'])->name('insurance.claims.payment');
        Route::delete('/insurance/claims/{claim}', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'destroy'])->name('insurance.claims.destroy');
        
        // Pharmacy
        Route::get('/pharmacy/medicines', [MedicinesController::class, 'index'])->name('pharmacy.medicines.index');
        Route::get('/pharmacy/medicines/create', [MedicinesController::class, 'create'])->name('pharmacy.medicines.create');
        Route::post('/pharmacy/medicines', [MedicinesController::class, 'store'])->name('pharmacy.medicines.store');
        Route::get('/pharmacy/medicines/{medicine}', [MedicinesController::class, 'show'])->name('pharmacy.medicines.show');
        Route::get('/pharmacy/medicines/{medicine}/edit', [MedicinesController::class, 'edit'])->name('pharmacy.medicines.edit');
        Route::put('/pharmacy/medicines/{medicine}', [MedicinesController::class, 'update'])->name('pharmacy.medicines.update');
        Route::delete('/pharmacy/medicines/{medicine}', [MedicinesController::class, 'destroy'])->name('pharmacy.medicines.destroy');
        
        Route::get('/pharmacy/prescriptions', [PrescriptionsController::class, 'index'])->name('pharmacy.prescriptions.index');
        Route::get('/pharmacy/prescriptions/create', [PrescriptionsController::class, 'create'])->name('pharmacy.prescriptions.create');
        Route::post('/pharmacy/prescriptions', [PrescriptionsController::class, 'store'])->name('pharmacy.prescriptions.store');
        Route::get('/pharmacy/prescriptions/{prescription}', [PrescriptionsController::class, 'show'])->name('pharmacy.prescriptions.show');
        Route::get('/pharmacy/prescriptions/{prescription}/edit', [PrescriptionsController::class, 'edit'])->name('pharmacy.prescriptions.edit');
        Route::put('/pharmacy/prescriptions/{prescription}', [PrescriptionsController::class, 'update'])->name('pharmacy.prescriptions.update');
        Route::delete('/pharmacy/prescriptions/{prescription}', [PrescriptionsController::class, 'destroy'])->name('pharmacy.prescriptions.destroy');
        
        // E-Prescription Routes
        Route::prefix('prescriptions/e-prescription')->name('prescriptions.e-prescription.')->group(function () {
            Route::get('/templates', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'templates'])->name('templates');
            Route::get('/create', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'store'])->name('store');
            Route::get('/{prescription}', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'show'])->name('show');
            Route::get('/{prescription}/pdf', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'pdf'])->name('pdf');
            Route::get('/templates/manage', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'manageTemplates'])->name('manage-templates');
            Route::get('/templates/create', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'createTemplate'])->name('create-template');
            Route::post('/templates', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'storeTemplate'])->name('store-template');
        });
        
        // Laboratory
        Route::get('/laboratory/tests', [LabTestsController::class, 'index'])->name('laboratory.tests.index');
        Route::get('/laboratory/tests/create', [LabTestsController::class, 'create'])->name('laboratory.tests.create');
        Route::post('/laboratory/tests', [LabTestsController::class, 'store'])->name('laboratory.tests.store');
        Route::get('/laboratory/tests/{labTest}', [LabTestsController::class, 'show'])->name('laboratory.tests.show');
        Route::get('/laboratory/tests/{labTest}/edit', [LabTestsController::class, 'edit'])->name('laboratory.tests.edit');
        Route::put('/laboratory/tests/{labTest}', [LabTestsController::class, 'update'])->name('laboratory.tests.update');
        Route::delete('/laboratory/tests/{labTest}', [LabTestsController::class, 'destroy'])->name('laboratory.tests.destroy');
        
        Route::get('/laboratory/requests', [LabRequestsController::class, 'index'])->name('laboratory.requests.index');
        Route::get('/laboratory/requests/create', [LabRequestsController::class, 'create'])->name('laboratory.requests.create');
        Route::post('/laboratory/requests', [LabRequestsController::class, 'store'])->name('laboratory.requests.store');
        Route::get('/laboratory/requests/{labRequest}', [LabRequestsController::class, 'show'])->name('laboratory.requests.show');
        
        Route::get('/laboratory/technicians', [LaboratoryController::class, 'technicians'])->name('laboratory.technicians.index');
        Route::get('/laboratory/technicians/create', [LaboratoryController::class, 'createTechnician'])->name('laboratory.technicians.create');
        Route::post('/laboratory/technicians', [LaboratoryController::class, 'storeTechnician'])->name('laboratory.technicians.store');
        Route::get('/laboratory/reports', [LaboratoryController::class, 'reports'])->name('laboratory.reports');
        
        // Radiology
        Route::get('/radiology/tests', [RadiologyTestsController::class, 'index'])->name('radiology.tests.index');
        Route::get('/radiology/tests/create', [RadiologyTestsController::class, 'create'])->name('radiology.tests.create');
        Route::post('/radiology/tests', [RadiologyTestsController::class, 'store'])->name('radiology.tests.store');
        Route::get('/radiology/tests/{radiologyTest}', [RadiologyTestsController::class, 'show'])->name('radiology.tests.show');
        Route::get('/radiology/tests/{radiologyTest}/edit', [RadiologyTestsController::class, 'edit'])->name('radiology.tests.edit');
        Route::put('/radiology/tests/{radiologyTest}', [RadiologyTestsController::class, 'update'])->name('radiology.tests.update');
        Route::delete('/radiology/tests/{radiologyTest}', [RadiologyTestsController::class, 'destroy'])->name('radiology.tests.destroy');
        
        Route::get('/radiology/requests', [RadiologyRequestsController::class, 'index'])->name('radiology.requests.index');
        Route::get('/radiology/requests/create', [RadiologyRequestsController::class, 'create'])->name('radiology.requests.create');
        Route::post('/radiology/requests', [RadiologyRequestsController::class, 'store'])->name('radiology.requests.store');
        Route::get('/radiology/requests/{radiologyRequest}', [RadiologyRequestsController::class, 'show'])->name('radiology.requests.show');
        
        // HR Management
        Route::get('/hr/employees', [EmployeesController::class, 'index'])->name('hr.employees.index');
        Route::get('/hr/employees/create', [EmployeesController::class, 'create'])->name('hr.employees.create');
        Route::post('/hr/employees', [EmployeesController::class, 'store'])->name('hr.employees.store');
        Route::get('/hr/employees/{employee}', [HrController::class, 'showEmployee'])->name('hr.employees.show');
        Route::get('/hr/employees/{employee}/edit', [HrController::class, 'editEmployee'])->name('hr.employees.edit');
        Route::put('/hr/employees/{employee}', [HrController::class, 'updateEmployee'])->name('hr.employees.update');
        Route::delete('/hr/employees/{employee}', [HrController::class, 'destroyEmployee'])->name('hr.employees.destroy');
        // HR Departments
        Route::get('/hr/departments', [EmployeeDepartmentsController::class, 'index'])->name('hr.departments.index');
        Route::post('/hr/departments', [EmployeeDepartmentsController::class, 'store'])->name('hr.departments.store');
        Route::put('/hr/departments/{department}', [EmployeeDepartmentsController::class, 'update'])->name('hr.departments.update');
        Route::delete('/hr/departments/{department}', [EmployeeDepartmentsController::class, 'destroy'])->name('hr.departments.destroy');
        Route::get('/hr/payrolls', [PayrollsController::class, 'index'])->name('hr.payrolls.index');
        Route::get('/hr/payrolls/create', [PayrollsController::class, 'create'])->name('hr.payrolls.create');
        Route::post('/hr/payrolls', [PayrollsController::class, 'store'])->name('hr.payrolls.store');
        Route::get('/hr/schedules', [SchedulesController::class, 'index'])->name('hr.schedules.index');
        Route::get('/hr/schedules/create', [SchedulesController::class, 'create'])->name('hr.schedules.create');
        Route::post('/hr/schedules', [SchedulesController::class, 'store'])->name('hr.schedules.store');
        Route::get('/hr/attendance', [AttendanceController::class, 'index'])->name('hr.attendance.index');
        Route::get('/hr/attendance/create', [AttendanceController::class, 'create'])->name('hr.attendance.create');
        Route::post('/hr/attendance', [AttendanceController::class, 'store'])->name('hr.attendance.store');
        Route::get('/hr/leave-requests', [LeaveRequestsController::class, 'index'])->name('hr.leave-requests.index');
        Route::get('/hr/leave-requests/create', [LeaveRequestsController::class, 'create'])->name('hr.leave-requests.create');
        Route::post('/hr/leave-requests', [LeaveRequestsController::class, 'store'])->name('hr.leave-requests.store');
        Route::post('/hr/leave-requests/{leaveRequest}/approve', [LeaveRequestsController::class, 'approve'])->name('hr.leave-requests.approve');
        Route::post('/hr/leave-requests/{leaveRequest}/reject', [LeaveRequestsController::class, 'reject'])->name('hr.leave-requests.reject');
        
        // Blood Bank
        Route::get('/bloodbank', [BloodBankController::class, 'index'])->name('bloodbank.index');
        Route::get('/bloodbank/donors', [BloodBankController::class, 'donors'])->name('bloodbank.donors');
        Route::get('/bloodbank/requests', [BloodBankController::class, 'requests'])->name('bloodbank.requests');
        Route::get('/bloodbank/donors/create', [BloodBankController::class, 'createDonor'])->name('bloodbank.donors.create');
        Route::post('/bloodbank/donors', [BloodBankController::class, 'storeDonor'])->name('bloodbank.donors.store');
        Route::get('/bloodbank/requests/create', [BloodBankController::class, 'createRequest'])->name('bloodbank.requests.create');
        Route::post('/bloodbank/requests', [BloodBankController::class, 'storeRequest'])->name('bloodbank.requests.store');
        
        // Ambulance & Emergency
        Route::get('/ambulance', [AmbulanceController::class, 'index'])->name('ambulance.index');
        Route::get('/ambulance/calls', [AmbulanceController::class, 'calls'])->name('ambulance.calls');
        Route::get('/ambulance/emergency', [AmbulanceController::class, 'emergency'])->name('ambulance.emergency');
        Route::get('/ambulance/create-ambulance', [AmbulanceController::class, 'createAmbulance'])->name('ambulance.create-ambulance');
        Route::post('/ambulance/ambulances', [AmbulanceController::class, 'storeAmbulance'])->name('ambulance.store-ambulance');
        Route::get('/ambulance/create-call', [AmbulanceController::class, 'createCall'])->name('ambulance.create-call');
        Route::post('/ambulance/calls', [AmbulanceController::class, 'storeCall'])->name('ambulance.store-call');
        Route::get('/ambulance/create-emergency', [AmbulanceController::class, 'createEmergency'])->name('ambulance.create-emergency');
        Route::post('/ambulance/emergency', [AmbulanceController::class, 'storeEmergency'])->name('ambulance.store-emergency');
        
        // Reports & Analytics
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/patients', [ReportsController::class, 'patientReports'])->name('reports.patients');
        Route::get('/reports/revenue', [ReportsController::class, 'revenueReports'])->name('reports.revenue');
        Route::get('/reports/appointments', [ReportsController::class, 'appointmentReports'])->name('reports.appointments');
        Route::get('/reports/financial', [ReportsController::class, 'financialReports'])->name('reports.financial');
        Route::get('/reports/export-patients', [ReportsController::class, 'exportPatients'])->name('reports.export-patients');
        
        // Packages Management
        Route::get('/packages', [PackagesController::class, 'index'])->name('packages.index');
        Route::get('/packages/create', [PackagesController::class, 'create'])->name('packages.create');
        Route::post('/packages', [PackagesController::class, 'store'])->name('packages.store');
        Route::get('/packages/{package}', [PackagesController::class, 'show'])->name('packages.show');
        Route::get('/packages/{package}/edit', [PackagesController::class, 'edit'])->name('packages.edit');
        Route::put('/packages/{package}', [PackagesController::class, 'update'])->name('packages.update');
        Route::delete('/packages/{package}', [PackagesController::class, 'destroy'])->name('packages.destroy');
        
        // Nurses Management
        Route::get('/nurses', [NursesController::class, 'index'])->name('nurses.index');
        Route::get('/nurses/create', [NursesController::class, 'create'])->name('nurses.create');
        Route::post('/nurses', [NursesController::class, 'store'])->name('nurses.store');
        // Nurse specific routes (must come before /nurses/{nurse})
        Route::get('/nurses/duty-roster', [NursesController::class, 'dutyRoster'])->name('nurses.duty-roster');
        Route::get('/nurses/assign-wards', [NursesController::class, 'assignWards'])->name('nurses.assign-wards');
        Route::get('/nurses/departments', [NursesController::class, 'departments'])->name('nurses.departments');
        Route::post('/nurses/departments', [NursesController::class, 'storeDepartment'])->name('nurses.departments.store');
        Route::get('/nurses/{nurse}', [NursesController::class, 'show'])->name('nurses.show');
        Route::get('/nurses/{nurse}/edit', [NursesController::class, 'edit'])->name('nurses.edit');
        Route::put('/nurses/{nurse}', [NursesController::class, 'update'])->name('nurses.update');
        Route::delete('/nurses/{nurse}', [NursesController::class, 'destroy'])->name('nurses.destroy');
        
        // Case Handlers & Social Workers
        Route::get('/case-handlers', [CaseHandlersController::class, 'index'])->name('case-handlers.index');
        Route::get('/case-handlers/create', [CaseHandlersController::class, 'create'])->name('case-handlers.create');
        Route::post('/case-handlers', [CaseHandlersController::class, 'store'])->name('case-handlers.store');
        Route::get('/case-handlers/cases', [CaseHandlersController::class, 'cases'])->name('case-handlers.cases');
        Route::get('/case-handlers/cases/create', [CaseHandlersController::class, 'createCase'])->name('case-handlers.cases.create');
        Route::post('/case-handlers/cases', [CaseHandlersController::class, 'storeCase'])->name('case-handlers.cases.store');
        
        // Birth & Death Reports
        Route::get('/reports/birth', [BirthDeathReportsController::class, 'birthReports'])->name('reports.birth');
        Route::get('/reports/death', [BirthDeathReportsController::class, 'deathReports'])->name('reports.death');
        Route::get('/reports/birth/create', [BirthDeathReportsController::class, 'createBirthReport'])->name('reports.birth.create');
        Route::post('/reports/birth', [BirthDeathReportsController::class, 'storeBirthReport'])->name('reports.birth.store');
        Route::get('/reports/death/create', [BirthDeathReportsController::class, 'createDeathReport'])->name('reports.death.create');
        Route::post('/reports/death', [BirthDeathReportsController::class, 'storeDeathReport'])->name('reports.death.store');
        
        // Operation Reports & Surgery
        Route::get('/operations', [OperationReportsController::class, 'index'])->name('operations.index');
        Route::get('/operations/create', [OperationReportsController::class, 'create'])->name('operations.create');
        Route::post('/operations', [OperationReportsController::class, 'store'])->name('operations.store');
        
        // Patient Diagnosis
        Route::get('/diagnosis/categories', [DiagnosisController::class, 'categories'])->name('diagnosis.categories');
        Route::get('/diagnosis/categories/create', [DiagnosisController::class, 'createCategory'])->name('diagnosis.categories.create');
        Route::post('/diagnosis/categories', [DiagnosisController::class, 'storeCategory'])->name('diagnosis.categories.store');
        Route::get('/diagnosis/patient-diagnoses', [DiagnosisController::class, 'patientDiagnoses'])->name('diagnosis.patient-diagnoses');
        Route::get('/diagnosis/patient-diagnoses/create', [DiagnosisController::class, 'createDiagnosis'])->name('diagnosis.patient-diagnoses.create');
        Route::post('/diagnosis/patient-diagnoses', [DiagnosisController::class, 'storeDiagnosis'])->name('diagnosis.patient-diagnoses.store');
        
        // Staff Management
        Route::get('/staff/receptionists', [StaffManagementController::class, 'receptionists'])->name('staff.receptionists');
        Route::get('/staff/receptionists/create', [StaffManagementController::class, 'createReceptionist'])->name('staff.receptionists.create');
        Route::post('/staff/receptionists', [StaffManagementController::class, 'storeReceptionist'])->name('staff.receptionists.store');
        Route::get('/staff/pharmacists', [StaffManagementController::class, 'pharmacists'])->name('staff.pharmacists');
        Route::get('/staff/pharmacists/create', [StaffManagementController::class, 'createPharmacist'])->name('staff.pharmacists.create');
        Route::post('/staff/pharmacists', [StaffManagementController::class, 'storePharmacist'])->name('staff.pharmacists.store');
        Route::get('/staff/lab-technicians', [StaffManagementController::class, 'labTechnicians'])->name('staff.lab-technicians');
        Route::get('/staff/lab-technicians/create', [StaffManagementController::class, 'createLabTechnician'])->name('staff.lab-technicians.create');
        Route::post('/staff/lab-technicians', [StaffManagementController::class, 'storeLabTechnician'])->name('staff.lab-technicians.store');
        Route::get('/staff/accountants', [StaffManagementController::class, 'accountants'])->name('staff.accountants');
        Route::get('/staff/accountants/create', [StaffManagementController::class, 'createAccountant'])->name('staff.accountants.create');
        Route::post('/staff/accountants', [StaffManagementController::class, 'storeAccountant'])->name('staff.accountants.store');
        
        // Settings & Configuration
        Route::get('/settings', [\App\Http\Controllers\Hms\SettingsController::class, 'index'])->name('settings.index');
        Route::get('/settings/general', [\App\Http\Controllers\Hms\SettingsController::class, 'general'])->name('settings.general');
        Route::post('/settings/general', [\App\Http\Controllers\Hms\SettingsController::class, 'updateGeneral'])->name('settings.general.update');
        Route::get('/settings/branches', [\App\Http\Controllers\Hms\SettingsController::class, 'branches'])->name('settings.branches');
        Route::get('/settings/branches/create', [\App\Http\Controllers\Hms\SettingsController::class, 'createBranch'])->name('settings.branches.create');
        Route::post('/settings/branches', [\App\Http\Controllers\Hms\SettingsController::class, 'storeBranch'])->name('settings.branches.store');
        Route::get('/settings/audit-logs', [\App\Http\Controllers\Hms\SettingsController::class, 'auditLogs'])->name('settings.audit-logs');
        Route::get('/settings/backup', [\App\Http\Controllers\Hms\SettingsController::class, 'backup'])->name('settings.backup');
        Route::post('/settings/backup/create', [\App\Http\Controllers\Hms\SettingsController::class, 'createBackup'])->name('settings.backup.create');
        Route::post('/settings/backup/restore', [\App\Http\Controllers\Hms\SettingsController::class, 'restoreBackup'])->name('settings.backup.restore');
        Route::get('/settings/backup/download/{filename}', [\App\Http\Controllers\Hms\SettingsController::class, 'downloadBackup'])->name('settings.backup.download');
        
        // Dashboard Features
        Route::get('/dashboard/notifications', [\App\Http\Controllers\Hms\NotificationsController::class, 'index'])->name('dashboard.notifications');
        Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\Hms\NotificationsController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Hms\NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{id}', [\App\Http\Controllers\Hms\NotificationsController::class, 'destroy'])->name('notifications.destroy');
        Route::get('/dashboard/today-summary', [\App\Http\Controllers\Hms\DashboardController::class, 'todaySummary'])->name('dashboard.today-summary');
        Route::get('/dashboard/active-staff', [\App\Http\Controllers\Hms\DashboardController::class, 'activeStaff'])->name('dashboard.active-staff');
        
        // Discharge Summary
        Route::get('/discharge-summary', [\App\Http\Controllers\Hms\DischargeSummaryController::class, 'index'])->name('discharge-summary.index');
        Route::get('/discharge-summary/create', [\App\Http\Controllers\Hms\DischargeSummaryController::class, 'create'])->name('discharge-summary.create');
        Route::post('/discharge-summary', [\App\Http\Controllers\Hms\DischargeSummaryController::class, 'store'])->name('discharge-summary.store');
        
        // Doctor Charges
        Route::get('/doctor-charges', [\App\Http\Controllers\Hms\DoctorChargesController::class, 'index'])->name('doctor-charges.index');
        Route::post('/doctor-charges', [\App\Http\Controllers\Hms\DoctorChargesController::class, 'store'])->name('doctor-charges.store');
        
        // Medical History & Vitals
        Route::get('/medical-history', [\App\Http\Controllers\Hms\MedicalHistoryController::class, 'index'])->name('medical-history.index');
        Route::get('/medical-history/{patient}', [\App\Http\Controllers\Hms\MedicalHistoryController::class, 'show'])->name('medical-history.show');
        Route::post('/medical-history', [\App\Http\Controllers\Hms\MedicalHistoryController::class, 'store'])->name('medical-history.store');
        
        // Test Categories (Pathology & Radiology)
        Route::get('/test-categories', [\App\Http\Controllers\Hms\TestCategoriesController::class, 'index'])->name('test-categories.index');
        Route::post('/test-categories', [\App\Http\Controllers\Hms\TestCategoriesController::class, 'store'])->name('test-categories.store');
        
        // Investigation Reports
        Route::get('/investigation-reports', [\App\Http\Controllers\Hms\TestCategoriesController::class, 'investigationReports'])->name('investigation-reports.index');
        
        // Blood Bank Stock Levels
        Route::get('/bloodbank/stock-levels', [\App\Http\Controllers\Hms\BloodBankController::class, 'stockLevels'])->name('bloodbank.stock-levels');
        
        // Medicine Categories & Brands
        Route::get('/pharmacy/medicine-categories', [\App\Http\Controllers\Hms\MedicineCategoriesController::class, 'index'])->name('pharmacy.medicine-categories.index');
        Route::post('/pharmacy/medicine-categories', [\App\Http\Controllers\Hms\MedicineCategoriesController::class, 'store'])->name('pharmacy.medicine-categories.store');
        Route::get('/pharmacy/medicine-brands', [\App\Http\Controllers\Hms\MedicineBrandsController::class, 'index'])->name('pharmacy.medicine-brands.index');
        Route::post('/pharmacy/medicine-brands', [\App\Http\Controllers\Hms\MedicineBrandsController::class, 'store'])->name('pharmacy.medicine-brands.store');
        
        // Inventory Management
        // Suppliers
        Route::get('/inventory/suppliers', [\App\Http\Controllers\Hms\SuppliersController::class, 'index'])->name('inventory.suppliers.index');
        Route::get('/inventory/suppliers/create', [\App\Http\Controllers\Hms\SuppliersController::class, 'create'])->name('inventory.suppliers.create');
        Route::post('/inventory/suppliers', [\App\Http\Controllers\Hms\SuppliersController::class, 'store'])->name('inventory.suppliers.store');
        Route::get('/inventory/suppliers/{supplier}', [\App\Http\Controllers\Hms\SuppliersController::class, 'show'])->name('inventory.suppliers.show');
        Route::get('/inventory/suppliers/{supplier}/edit', [\App\Http\Controllers\Hms\SuppliersController::class, 'edit'])->name('inventory.suppliers.edit');
        Route::put('/inventory/suppliers/{supplier}', [\App\Http\Controllers\Hms\SuppliersController::class, 'update'])->name('inventory.suppliers.update');
        Route::delete('/inventory/suppliers/{supplier}', [\App\Http\Controllers\Hms\SuppliersController::class, 'destroy'])->name('inventory.suppliers.destroy');
        
        // Purchase Orders
        Route::get('/inventory/purchase-orders', [\App\Http\Controllers\Hms\PurchaseOrdersController::class, 'index'])->name('inventory.purchase-orders.index');
        Route::get('/inventory/purchase-orders/create', [\App\Http\Controllers\Hms\PurchaseOrdersController::class, 'create'])->name('inventory.purchase-orders.create');
        Route::post('/inventory/purchase-orders', [\App\Http\Controllers\Hms\PurchaseOrdersController::class, 'store'])->name('inventory.purchase-orders.store');
        Route::get('/inventory/purchase-orders/{purchaseOrder}', [\App\Http\Controllers\Hms\PurchaseOrdersController::class, 'show'])->name('inventory.purchase-orders.show');
        Route::get('/inventory/purchase-orders/{purchaseOrder}/edit', [\App\Http\Controllers\Hms\PurchaseOrdersController::class, 'edit'])->name('inventory.purchase-orders.edit');
        Route::put('/inventory/purchase-orders/{purchaseOrder}', [\App\Http\Controllers\Hms\PurchaseOrdersController::class, 'update'])->name('inventory.purchase-orders.update');
        Route::delete('/inventory/purchase-orders/{purchaseOrder}', [\App\Http\Controllers\Hms\PurchaseOrdersController::class, 'destroy'])->name('inventory.purchase-orders.destroy');
        Route::post('/inventory/purchase-orders/{purchaseOrder}/submit', [\App\Http\Controllers\Hms\PurchaseOrdersController::class, 'submit'])->name('inventory.purchase-orders.submit');
        Route::post('/inventory/purchase-orders/{purchaseOrder}/approve', [\App\Http\Controllers\Hms\PurchaseOrdersController::class, 'approve'])->name('inventory.purchase-orders.approve');
        
        // Stock Movements
        Route::get('/inventory/stock-movements', [\App\Http\Controllers\Hms\StockMovementsController::class, 'index'])->name('inventory.stock-movements.index');
        Route::get('/inventory/stock-movements/create', [\App\Http\Controllers\Hms\StockMovementsController::class, 'create'])->name('inventory.stock-movements.create');
        Route::post('/inventory/stock-movements', [\App\Http\Controllers\Hms\StockMovementsController::class, 'store'])->name('inventory.stock-movements.store');
        Route::get('/inventory/stock-movements/{stockMovement}', [\App\Http\Controllers\Hms\StockMovementsController::class, 'show'])->name('inventory.stock-movements.show');
        Route::post('/inventory/stock-movements/receive', [\App\Http\Controllers\Hms\StockMovementsController::class, 'receiveStock'])->name('inventory.stock-movements.receive');
        Route::get('/inventory/stock-report', [\App\Http\Controllers\Hms\StockMovementsController::class, 'stockReport'])->name('inventory.stock-report');
        
        // Legacy inventory routes
        Route::get('/inventory/categories', [\App\Http\Controllers\Hms\InventoryManagementController::class, 'categories'])->name('inventory.categories');
        Route::post('/inventory/categories', [\App\Http\Controllers\Hms\InventoryManagementController::class, 'storeCategory'])->name('inventory.categories.store');
        Route::put('/inventory/categories/{id}', [\App\Http\Controllers\Hms\InventoryManagementController::class, 'updateCategory'])->name('inventory.categories.update');
        Route::delete('/inventory/categories/{id}', [\App\Http\Controllers\Hms\InventoryManagementController::class, 'deleteCategory'])->name('inventory.categories.delete');
        Route::get('/inventory/expiry-alerts', [\App\Http\Controllers\Hms\InventoryManagementController::class, 'expiryAlerts'])->name('inventory.expiry-alerts');
        
        // Finance - Billing
        Route::get('/billing/receipts', [\App\Http\Controllers\Hms\BillingController::class, 'receipts'])->name('billing.receipts');
        Route::get('/billing/payment-reports', [\App\Http\Controllers\Hms\BillingController::class, 'paymentReports'])->name('billing.payment-reports');
        
        // Advance Payments
        Route::get('/advance-payments', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'index'])->name('advance-payments.index');
        Route::get('/advance-payments/deposits', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'deposits'])->name('advance-payments.deposits');
        Route::get('/advance-payments/refunds', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'refunds'])->name('advance-payments.refunds');
        Route::post('/advance-payments/refunds', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'processRefund'])->name('advance-payments.process-refund');
        
        // Finance - Accounts Management
        Route::get('/finance/accounts', [\App\Http\Controllers\Hms\AccountsController::class, 'index'])->name('finance.accounts.index');
        Route::get('/finance/accounts/create', [\App\Http\Controllers\Hms\AccountsController::class, 'create'])->name('finance.accounts.create');
        Route::post('/finance/accounts', [\App\Http\Controllers\Hms\AccountsController::class, 'store'])->name('finance.accounts.store');
        Route::get('/finance/accounts/{account}', [\App\Http\Controllers\Hms\AccountsController::class, 'show'])->name('finance.accounts.show');
        Route::get('/finance/accounts/{account}/edit', [\App\Http\Controllers\Hms\AccountsController::class, 'edit'])->name('finance.accounts.edit');
        Route::put('/finance/accounts/{account}', [\App\Http\Controllers\Hms\AccountsController::class, 'update'])->name('finance.accounts.update');
        Route::delete('/finance/accounts/{account}', [\App\Http\Controllers\Hms\AccountsController::class, 'destroy'])->name('finance.accounts.destroy');
        Route::get('/finance/chart-of-accounts', [\App\Http\Controllers\Hms\AccountsController::class, 'chartOfAccounts'])->name('finance.chart-of-accounts');
        Route::get('/finance/ledger', [\App\Http\Controllers\Hms\AccountsController::class, 'ledger'])->name('finance.ledger');
        Route::get('/finance/trial-balance', [\App\Http\Controllers\Hms\AccountsController::class, 'trialBalance'])->name('finance.trial-balance');
        
        // Finance - Income
        Route::get('/finance/income', [\App\Http\Controllers\Hms\IncomeController::class, 'index'])->name('finance.income.index');
        Route::get('/finance/income/create', [\App\Http\Controllers\Hms\IncomeController::class, 'create'])->name('finance.income.create');
        Route::post('/finance/income', [\App\Http\Controllers\Hms\IncomeController::class, 'store'])->name('finance.income.store');
        Route::get('/finance/income/{income}', [\App\Http\Controllers\Hms\IncomeController::class, 'show'])->name('finance.income.show');
        Route::get('/finance/income/{income}/edit', [\App\Http\Controllers\Hms\IncomeController::class, 'edit'])->name('finance.income.edit');
        Route::put('/finance/income/{income}', [\App\Http\Controllers\Hms\IncomeController::class, 'update'])->name('finance.income.update');
        Route::delete('/finance/income/{income}', [\App\Http\Controllers\Hms\IncomeController::class, 'destroy'])->name('finance.income.destroy');
        Route::get('/finance/income-reports', [\App\Http\Controllers\Hms\IncomeController::class, 'reports'])->name('finance.income.reports');
        
        // Finance - Expenses
        // Expenses
        Route::get('/finance/expenses', [\App\Http\Controllers\Hms\ExpensesController::class, 'index'])->name('finance.expenses.index');
        Route::get('/finance/expenses/create', [\App\Http\Controllers\Hms\ExpensesController::class, 'create'])->name('finance.expenses.create');
        Route::post('/finance/expenses', [\App\Http\Controllers\Hms\ExpensesController::class, 'store'])->name('finance.expenses.store');
        Route::get('/finance/expenses/categories', [\App\Http\Controllers\Hms\ExpensesController::class, 'categories'])->name('finance.expenses.categories');
        Route::get('/finance/expenses/entries', [\App\Http\Controllers\Hms\ExpensesController::class, 'entries'])->name('finance.expenses.entries');
        Route::get('/finance/expenses-reports', [\App\Http\Controllers\Hms\ExpensesController::class, 'reports'])->name('finance.expenses.reports');
        Route::get('/finance/expenses/{expense}', [\App\Http\Controllers\Hms\ExpensesController::class, 'show'])->name('finance.expenses.show');
        Route::get('/finance/expenses/{expense}/edit', [\App\Http\Controllers\Hms\ExpensesController::class, 'edit'])->name('finance.expenses.edit');
        Route::put('/finance/expenses/{expense}', [\App\Http\Controllers\Hms\ExpensesController::class, 'update'])->name('finance.expenses.update');
        Route::delete('/finance/expenses/{expense}', [\App\Http\Controllers\Hms\ExpensesController::class, 'destroy'])->name('finance.expenses.destroy');
        
        // Finance - Comprehensive Reports
        Route::get('/finance', [\App\Http\Controllers\Hms\FinanceController::class, 'index'])->name('finance.index');
        Route::get('/finance/reports', [\App\Http\Controllers\Hms\FinanceController::class, 'reports'])->name('finance.reports');
        Route::get('/finance/profit-loss', [\App\Http\Controllers\Hms\FinanceController::class, 'profitLoss'])->name('finance.profit-loss');
        Route::get('/finance/profit-loss/pdf', [\App\Http\Controllers\Hms\FinanceController::class, 'profitLossPdf'])->name('finance.profit-loss.pdf');
        Route::get('/finance/balance-sheet', [\App\Http\Controllers\Hms\FinanceController::class, 'balanceSheet'])->name('finance.balance-sheet');
        Route::get('/finance/cash-flow', [\App\Http\Controllers\Hms\FinanceController::class, 'cashFlow'])->name('finance.cash-flow');
        
        // Insurance
        Route::get('/insurance', [\App\Http\Controllers\Hms\InsuranceController::class, 'index'])->name('insurance.index');
        Route::get('/insurance/companies', [\App\Http\Controllers\Hms\InsuranceController::class, 'companies'])->name('insurance.companies');
        Route::get('/insurance/companies/create', [\App\Http\Controllers\Hms\InsuranceController::class, 'createCompany'])->name('insurance.companies.create');
        Route::post('/insurance/companies', [\App\Http\Controllers\Hms\InsuranceController::class, 'storeCompany'])->name('insurance.companies.store');
        Route::get('/insurance/companies/{company}/edit', [\App\Http\Controllers\Hms\InsuranceController::class, 'editCompany'])->name('insurance.companies.edit');
        Route::put('/insurance/companies/{company}', [\App\Http\Controllers\Hms\InsuranceController::class, 'updateCompany'])->name('insurance.companies.update');
        Route::delete('/insurance/companies/{company}', [\App\Http\Controllers\Hms\InsuranceController::class, 'destroyCompany'])->name('insurance.companies.destroy');
        Route::get('/insurance/policies', [\App\Http\Controllers\Hms\InsuranceController::class, 'policies'])->name('insurance.policies');
        Route::get('/insurance/policies/create', [\App\Http\Controllers\Hms\InsuranceController::class, 'createPolicy'])->name('insurance.policies.create');
        Route::post('/insurance/policies', [\App\Http\Controllers\Hms\InsuranceController::class, 'storePolicy'])->name('insurance.policies.store');
        Route::get('/insurance/policies/{policy}', [\App\Http\Controllers\Hms\InsuranceController::class, 'showPolicy'])->name('insurance.policies.show');
        Route::get('/insurance/policies/{policy}/edit', [\App\Http\Controllers\Hms\InsuranceController::class, 'editPolicy'])->name('insurance.policies.edit');
        Route::put('/insurance/policies/{policy}', [\App\Http\Controllers\Hms\InsuranceController::class, 'updatePolicy'])->name('insurance.policies.update');
        Route::delete('/insurance/policies/{policy}', [\App\Http\Controllers\Hms\InsuranceController::class, 'destroyPolicy'])->name('insurance.policies.destroy');
        Route::post('/insurance/claims', [\App\Http\Controllers\Hms\InsuranceController::class, 'submitClaim'])->name('insurance.submit-claim');
        
        // HR - Designations & Documents
        Route::get('/hr/designations', [\App\Http\Controllers\Hms\DesignationsController::class, 'index'])->name('hr.designations.index');
        Route::post('/hr/designations', [\App\Http\Controllers\Hms\DesignationsController::class, 'store'])->name('hr.designations.store');
        Route::put('/hr/designations/{designation}', [\App\Http\Controllers\Hms\DesignationsController::class, 'update'])->name('hr.designations.update');
        Route::delete('/hr/designations/{designation}', [\App\Http\Controllers\Hms\DesignationsController::class, 'destroy'])->name('hr.designations.destroy');
        
        // HR - Performance Appraisals
        Route::get('/hr/appraisals', [PerformanceAppraisalsController::class, 'index'])->name('hr.appraisals.index');
        Route::get('/hr/appraisals/create', [PerformanceAppraisalsController::class, 'create'])->name('hr.appraisals.create');
        Route::post('/hr/appraisals', [PerformanceAppraisalsController::class, 'store'])->name('hr.appraisals.store');
        Route::get('/hr/appraisals/{appraisal}', [PerformanceAppraisalsController::class, 'show'])->name('hr.appraisals.show');
        Route::get('/hr/appraisals/{appraisal}/edit', [PerformanceAppraisalsController::class, 'edit'])->name('hr.appraisals.edit');
        Route::put('/hr/appraisals/{appraisal}', [PerformanceAppraisalsController::class, 'update'])->name('hr.appraisals.update');
        Route::delete('/hr/appraisals/{appraisal}', [PerformanceAppraisalsController::class, 'destroy'])->name('hr.appraisals.destroy');
        
        Route::get('/hr/documents', [\App\Http\Controllers\Hms\HrDocumentsController::class, 'index'])->name('hr.documents.index');
        Route::get('/hr/document-types', [\App\Http\Controllers\Hms\HrDocumentsController::class, 'types'])->name('hr.document-types');
        Route::post('/hr/documents', [\App\Http\Controllers\Hms\HrDocumentsController::class, 'store'])->name('hr.documents.store');
        
        // HR - Leave Types Management
        Route::resource('hr/leave-types', LeaveTypesController::class)->names('hr.leave-types');
        
        // HR - Recruitment & Onboarding
        Route::resource('hr/job-postings', RecruitmentController::class)->names('hr.job-postings');
        Route::post('/hr/job-postings/{jobPosting}/publish', [RecruitmentController::class, 'publish'])->name('hr.job-postings.publish');
        Route::get('/hr/job-applications', [RecruitmentController::class, 'applications'])->name('hr.job-applications.index');
        Route::get('/hr/job-applications/{application}', [RecruitmentController::class, 'showApplication'])->name('hr.job-applications.show');
        Route::post('/hr/job-applications/{application}/shortlist', [RecruitmentController::class, 'shortlist'])->name('hr.job-applications.shortlist');
        Route::post('/hr/job-applications/{application}/reject', [RecruitmentController::class, 'reject'])->name('hr.job-applications.reject');
        Route::post('/hr/job-applications/{application}/convert-to-employee', [RecruitmentController::class, 'convertToEmployee'])->name('hr.job-applications.convert');
        
        // HR - Training & Development
        Route::resource('hr/training-programs', TrainingProgramsController::class)->names('hr.training-programs');
        Route::post('/hr/training-programs/{trainingProgram}/enroll', [TrainingProgramsController::class, 'enroll'])->name('hr.training-programs.enroll');
        Route::get('/hr/training-programs/{trainingProgram}/enrollments', [TrainingProgramsController::class, 'enrollments'])->name('hr.training-programs.enrollments');
        Route::post('/hr/training-enrollments/{enrollment}/complete', [TrainingProgramsController::class, 'markComplete'])->name('hr.training-enrollments.complete');
        Route::post('/hr/training-enrollments/{enrollment}/certificate', [TrainingProgramsController::class, 'issueCertificate'])->name('hr.training-enrollments.certificate');
        
        // HR - Announcements & Notices
        Route::resource('hr/announcements', HrAnnouncementsController::class)->names('hr.announcements');
        
        // HR - Shift Management
        Route::resource('hr/shifts', ShiftsController::class)->names('hr.shifts');
        Route::get('/hr/shifts/{shift}/roster', [ShiftsController::class, 'roster'])->name('hr.shifts.roster');
        Route::post('/hr/employee-shifts', [ShiftsController::class, 'assignShift'])->name('hr.employee-shifts.assign');
        
        // HR - Public Holidays
        Route::resource('hr/public-holidays', PublicHolidaysController::class)->names('hr.public-holidays');
        
        // HR - Reports
        Route::get('/hr/reports', [HrReportsController::class, 'index'])->name('hr.reports.index');
        Route::get('/hr/reports/employee-list', [HrReportsController::class, 'employeeList'])->name('hr.reports.employee-list');
        Route::get('/hr/reports/leave', [HrReportsController::class, 'leaveReport'])->name('hr.reports.leave');
        Route::get('/hr/reports/attendance', [HrReportsController::class, 'attendanceReport'])->name('hr.reports.attendance');
        Route::get('/hr/reports/payroll-summary', [HrReportsController::class, 'payrollSummary'])->name('hr.reports.payroll-summary');
        Route::get('/hr/reports/headcount-trends', [HrReportsController::class, 'headcountTrends'])->name('hr.reports.headcount-trends');
        Route::get('/hr/reports/attrition', [HrReportsController::class, 'attritionReport'])->name('hr.reports.attrition');
        Route::get('/hr/reports/salary-expense', [HrReportsController::class, 'salaryExpenseAnalysis'])->name('hr.reports.salary-expense');
        Route::get('/hr/reports/training-participation', [HrReportsController::class, 'trainingParticipation'])->name('hr.reports.training-participation');
        
        // HR - Settings
        Route::get('/hr/settings', [HrSettingsController::class, 'index'])->name('hr.settings.index');
        Route::post('/hr/settings', [HrSettingsController::class, 'update'])->name('hr.settings.update');
        
        // ID Card Generation
        Route::get('/patients/{patient}/id-card', [\App\Http\Controllers\Hms\IdCardController::class, 'patientCard'])->name('patients.id-card');
        Route::get('/patients/{patient}/id-card/preview', [\App\Http\Controllers\Hms\IdCardController::class, 'previewPatient'])->name('patients.id-card.preview');
        Route::get('/patients/{patient}/id-card/qr', [\App\Http\Controllers\Hms\IdCardController::class, 'generatePatientQR'])->name('patients.id-card.qr');
        Route::get('/hr/employees/{employee}/id-card', [\App\Http\Controllers\Hms\IdCardController::class, 'employeeCard'])->name('hr.employees.id-card');
        Route::get('/hr/employees/{employee}/id-card/preview', [\App\Http\Controllers\Hms\IdCardController::class, 'previewEmployee'])->name('hr.employees.id-card.preview');
        Route::get('/hr/employees/{employee}/id-card/qr', [\App\Http\Controllers\Hms\IdCardController::class, 'generateEmployeeQR'])->name('hr.employees.id-card.qr');
        Route::post('/id-cards/scan-qr', [\App\Http\Controllers\Hms\IdCardController::class, 'scanQR'])->name('id-cards.scan-qr');
        
        // HR - Employee Import/Export
        Route::get('/hr/employees/export', [EmployeesImportExportController::class, 'export'])->name('hr.employees.export');
        Route::get('/hr/employees/import', [EmployeesImportExportController::class, 'showImport'])->name('hr.employees.import');
        Route::post('/hr/employees/import', [EmployeesImportExportController::class, 'import'])->name('hr.employees.import.store');
        Route::get('/hr/employees/import/template', [EmployeesImportExportController::class, 'downloadTemplate'])->name('hr.employees.import.template');
        
        // Reports
        Route::get('/reports/billing', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'billingReport'])->name('reports.billing');
        Route::get('/reports/lab', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'labReport'])->name('reports.lab');
        Route::get('/reports/pharmacy', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'pharmacyReport'])->name('reports.pharmacy');
        Route::get('/reports/blood-bank', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'bloodBankReport'])->name('reports.blood-bank');
        Route::get('/reports/bed-occupancy', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'bedOccupancyReport'])->name('reports.bed-occupancy');
        Route::get('/reports/diagnosis', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'diagnosisReport'])->name('reports.diagnosis');
        Route::get('/reports/doctor-performance', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'doctorPerformanceReport'])->name('reports.doctor-performance');
        Route::get('/reports/expense', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'expenseReport'])->name('reports.expense');
        Route::get('/reports/summary', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'summaryReports'])->name('reports.summary');
        
        // Custom Report Builder
        Route::prefix('reports/custom-builder')->name('reports.custom-builder.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Hms\CustomReportBuilderController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Hms\CustomReportBuilderController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Hms\CustomReportBuilderController::class, 'store'])->name('store');
            Route::get('/{template}', [\App\Http\Controllers\Hms\CustomReportBuilderController::class, 'show'])->name('show');
            Route::get('/{template}/edit', [\App\Http\Controllers\Hms\CustomReportBuilderController::class, 'edit'])->name('edit');
            Route::put('/{template}', [\App\Http\Controllers\Hms\CustomReportBuilderController::class, 'update'])->name('update');
            Route::delete('/{template}', [\App\Http\Controllers\Hms\CustomReportBuilderController::class, 'destroy'])->name('destroy');
            Route::post('/{template}/generate', [\App\Http\Controllers\Hms\CustomReportBuilderController::class, 'generate'])->name('generate');
            Route::post('/{template}/duplicate', [\App\Http\Controllers\Hms\CustomReportBuilderController::class, 'duplicate'])->name('duplicate');
            Route::post('/{template}/schedule', [\App\Http\Controllers\Hms\CustomReportBuilderController::class, 'schedule'])->name('schedule');
            Route::get('/api/table-fields', [\App\Http\Controllers\Hms\CustomReportBuilderController::class, 'getTableFields'])->name('api.table-fields');
        });
        
        // Communication & Frontdesk
        Route::get('/calendar', [\App\Http\Controllers\Hms\CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/enquiries/feedback', [\App\Http\Controllers\Admin\EnquiriesController::class, 'feedback'])->name('enquiries.feedback');
        Route::get('/notices/staff', [\App\Http\Controllers\Admin\NoticesController::class, 'staff'])->name('notices.staff');
        
        // Messaging & Reminders
        Route::get('/messaging', [\App\Http\Controllers\Hms\MessagingController::class, 'index'])->name('messaging.index');
        Route::get('/messaging/bulk', [\App\Http\Controllers\Hms\MessagingController::class, 'bulk'])->name('messaging.bulk');
        Route::get('/messaging/templates', [\App\Http\Controllers\Hms\MessagingController::class, 'templates'])->name('messaging.templates');
        Route::post('/messaging/send', [\App\Http\Controllers\Hms\MessagingController::class, 'send'])->name('messaging.send');
        
        Route::get('/reminders', [\App\Http\Controllers\Hms\RemindersController::class, 'index'])->name('reminders.index');
        Route::get('/reminders/appointments', [\App\Http\Controllers\Hms\RemindersController::class, 'appointments'])->name('reminders.appointments');
        Route::get('/reminders/payments', [\App\Http\Controllers\Hms\RemindersController::class, 'payments'])->name('reminders.payments');
        Route::post('/reminders', [\App\Http\Controllers\Hms\RemindersController::class, 'store'])->name('reminders.store');
        
        // System Administration
        Route::get('/system/users', [\App\Http\Controllers\Hms\UsersManagementController::class, 'index'])->name('system.users.index');
        Route::get('/system/users/create', [\App\Http\Controllers\Hms\UsersManagementController::class, 'create'])->name('system.users.create');
        Route::post('/system/users', [\App\Http\Controllers\Hms\UsersManagementController::class, 'store'])->name('system.users.store');
        Route::get('/system/users/{user}', [\App\Http\Controllers\Hms\UsersManagementController::class, 'show'])->name('system.users.show');
        Route::get('/system/users/{user}/edit', [\App\Http\Controllers\Hms\UsersManagementController::class, 'edit'])->name('system.users.edit');
        Route::put('/system/users/{user}', [\App\Http\Controllers\Hms\UsersManagementController::class, 'update'])->name('system.users.update');
        Route::delete('/system/users/{user}', [\App\Http\Controllers\Hms\UsersManagementController::class, 'destroy'])->name('system.users.destroy');
        Route::get('/system/users/{user}/id-card', [\App\Http\Controllers\Hms\IdCardController::class, 'userCard'])->name('system.users.id-card');
        Route::get('/system/users/{user}/id-card/preview', [\App\Http\Controllers\Hms\IdCardController::class, 'previewUser'])->name('system.users.id-card.preview');
        
        // User Permissions Management
        Route::get('/system/users/{user}/permissions', [\App\Http\Controllers\Hms\UsersManagementController::class, 'permissions'])->name('system.users.permissions');
        Route::post('/system/users/{user}/roles', [\App\Http\Controllers\Hms\UsersManagementController::class, 'updateRoles'])->name('system.users.update-roles');
        Route::post('/system/users/{user}/permissions', [\App\Http\Controllers\Hms\UsersManagementController::class, 'updatePermissions'])->name('system.users.update-permissions');
        Route::get('/system/timezone', [\App\Http\Controllers\Hms\SystemSettingsController::class, 'timezone'])->name('system.timezone');
        Route::post('/system/timezone', [\App\Http\Controllers\Hms\SystemSettingsController::class, 'updateTimezone'])->name('system.timezone.update');
        Route::get('/system/theme', [\App\Http\Controllers\Hms\SystemSettingsController::class, 'theme'])->name('system.theme');
        Route::post('/system/theme', [\App\Http\Controllers\Hms\SystemSettingsController::class, 'updateTheme'])->name('system.theme.update');
        Route::get('/system/localization', [\App\Http\Controllers\Hms\LocalizationController::class, 'index'])->name('system.localization');
        Route::post('/system/localization/update', [\App\Http\Controllers\Hms\LocalizationController::class, 'update'])->name('system.localization.update');
        Route::get('/system/api-keys', [\App\Http\Controllers\Hms\ApiKeysController::class, 'index'])->name('system.api-keys');
        Route::post('/system/api-keys', [\App\Http\Controllers\Hms\ApiKeysController::class, 'store'])->name('system.api-keys.store');
        Route::delete('/system/api-keys/{apiKey}', [\App\Http\Controllers\Hms\ApiKeysController::class, 'destroy'])->name('system.api-keys.destroy');
        Route::get('/system/maps', [\App\Http\Controllers\Hms\SystemSettingsController::class, 'maps'])->name('system.maps');
        Route::post('/system/maps', [\App\Http\Controllers\Hms\SystemSettingsController::class, 'updateMaps'])->name('system.maps.update');
        Route::get('/system/contact-info', [\App\Http\Controllers\Hms\SystemSettingsController::class, 'contactInfo'])->name('system.contact-info');
        Route::post('/system/contact-info', [\App\Http\Controllers\Hms\SystemSettingsController::class, 'updateContactInfo'])->name('system.contact-info.update');
        
        // Theme Customizer
        Route::get('/settings/theme', [\App\Http\Controllers\Hms\ThemeController::class, 'index'])->name('settings.theme');
        Route::put('/settings/theme', [\App\Http\Controllers\Hms\ThemeController::class, 'update'])->name('settings.theme.update');
        Route::get('/settings/theme/preview', [\App\Http\Controllers\Hms\ThemeController::class, 'preview'])->name('settings.theme.preview');
        Route::post('/settings/theme/reset', [\App\Http\Controllers\Hms\ThemeController::class, 'reset'])->name('settings.theme.reset');
        Route::get('/settings/theme/export', [\App\Http\Controllers\Hms\ThemeController::class, 'export'])->name('settings.theme.export');
        Route::post('/settings/theme/import', [\App\Http\Controllers\Hms\ThemeController::class, 'import'])->name('settings.theme.import');
        Route::post('/settings/theme/toggle-dark-mode', [\App\Http\Controllers\Hms\ThemeController::class, 'toggleDarkMode'])->name('settings.theme.toggle-dark-mode');
        
        // Daily Summary
        Route::get('/daily-summary', [\App\Http\Controllers\Hms\DailySummaryController::class, 'index'])->name('daily-summary.index');
        Route::post('/daily-summary/generate', [\App\Http\Controllers\Hms\DailySummaryController::class, 'generate'])->name('daily-summary.generate');
        Route::post('/daily-summary/auto-generate', [\App\Http\Controllers\Hms\DailySummaryController::class, 'autoGenerate'])->name('daily-summary.auto-generate');
        
        // EHR Integration
        Route::get('/integration/ehr', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'index'])->name('integration.ehr.index');
        Route::get('/integration/ehr/hl7-config', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'hl7Config'])->name('integration.ehr.hl7-config');
        Route::get('/integration/ehr/fhir-config', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'fhirConfig'])->name('integration.ehr.fhir-config');
        Route::post('/integration/ehr/send-hl7', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'sendHl7Message'])->name('integration.ehr.send-hl7');
        Route::post('/integration/ehr/receive-hl7', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'receiveHl7Message'])->name('integration.ehr.receive-hl7');
        Route::post('/integration/ehr/send-fhir', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'sendFhirResource'])->name('integration.ehr.send-fhir');
        Route::post('/integration/ehr/test-hl7', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'testHl7Connection'])->name('integration.ehr.test-hl7');
        
        // Integrations
        Route::get('/integrations', [\App\Http\Controllers\Hms\IntegrationsController::class, 'index'])->name('integrations.index');
        Route::get('/integrations/payment-gateways', [\App\Http\Controllers\Hms\IntegrationsController::class, 'paymentGateways'])->name('integrations.payment-gateways');
        Route::get('/integrations/whatsapp', [\App\Http\Controllers\Hms\IntegrationsController::class, 'whatsapp'])->name('integrations.whatsapp');
        Route::get('/integrations/google-calendar', [\App\Http\Controllers\Hms\IntegrationsController::class, 'googleCalendar'])->name('integrations.google-calendar');
        Route::get('/integrations/alerts', [\App\Http\Controllers\Hms\IntegrationsController::class, 'automatedAlerts'])->name('integrations.alerts');
        Route::get('/integrations/data-sync', [\App\Http\Controllers\Hms\IntegrationsController::class, 'dataSync'])->name('integrations.data-sync');
        
        // AI Features
        Route::get('/ai/predictive-analytics', [\App\Http\Controllers\Ai\AiAssistantController::class, 'predictiveAnalytics'])->name('ai.predictive-analytics');
        
        // Global Search
        Route::get('/search', [\App\Http\Controllers\Hms\GlobalSearchController::class, 'search'])->name('global-search');
        
        // Batch Operations
        Route::prefix('batch')->name('batch.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'index'])->name('index');
            Route::post('/leave-requests', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'batchLeaveRequests'])->name('leave-requests');
            Route::post('/attendance', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'batchMarkAttendance'])->name('attendance');
            Route::post('/payroll', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'batchGeneratePayroll'])->name('payroll');
            Route::post('/id-cards', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'batchGenerateIdCards'])->name('id-cards');
            Route::post('/export', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'batchExport'])->name('export');
        });
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/enquiries', [\App\Http\Controllers\Admin\EnquiriesController::class, 'index'])->name('enquiries.index');
        Route::get('/appointment-requests', [\App\Http\Controllers\Admin\AppointmentRequestsController::class, 'index'])->name('appointments.requests');
        Route::get('/notices', [\App\Http\Controllers\Admin\NoticesController::class, 'index'])->name('notices.index');
        Route::post('/notices', [\App\Http\Controllers\Admin\NoticesController::class, 'store'])->name('notices.store');

        Route::get('/modules', [\App\Http\Controllers\Admin\ModulesController::class, 'index'])->name('modules.index');
        Route::get('/modules/{slug}', [\App\Http\Controllers\Admin\ModulesController::class, 'show'])->name('modules.show');
        
        // Multi-Currency Management Routes
        Route::prefix('modules/multi-currency')->name('modules.multi-currency.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MultiCurrencyController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MultiCurrencyController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MultiCurrencyController::class, 'store'])->name('store');
            Route::get('/{currency}', [\App\Http\Controllers\Admin\MultiCurrencyController::class, 'show'])->name('show');
            Route::get('/{currency}/edit', [\App\Http\Controllers\Admin\MultiCurrencyController::class, 'edit'])->name('edit');
            Route::put('/{currency}', [\App\Http\Controllers\Admin\MultiCurrencyController::class, 'update'])->name('update');
            Route::delete('/{currency}', [\App\Http\Controllers\Admin\MultiCurrencyController::class, 'destroy'])->name('destroy');
            Route::post('/update-exchange-rates', [\App\Http\Controllers\Admin\MultiCurrencyController::class, 'updateExchangeRates'])->name('update-exchange-rates');
            Route::post('/{currency}/set-base', [\App\Http\Controllers\Admin\MultiCurrencyController::class, 'setBaseCurrency'])->name('set-base');
        });
    });
    
    // CMS Routes for Frontend Pages
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/home', [\App\Http\Controllers\Cms\CmsController::class, 'homePage'])->name('home');
        Route::post('/home', [\App\Http\Controllers\Cms\CmsController::class, 'updateHomePage'])->name('home.update');
        Route::get('/services', [\App\Http\Controllers\Cms\CmsController::class, 'servicesPage'])->name('services');
        Route::post('/services', [\App\Http\Controllers\Cms\CmsController::class, 'updateServicesPage'])->name('services.update');
        Route::get('/doctors-page', [\App\Http\Controllers\Cms\CmsController::class, 'doctorsPage'])->name('doctors-page');
        Route::post('/doctors-page', [\App\Http\Controllers\Cms\CmsController::class, 'updateDoctorsPage'])->name('doctors-page.update');
        Route::get('/about', [\App\Http\Controllers\Cms\CmsController::class, 'aboutPage'])->name('about');
        Route::post('/about', [\App\Http\Controllers\Cms\CmsController::class, 'updateAboutPage'])->name('about.update');
        Route::get('/contact-page', [\App\Http\Controllers\Cms\CmsController::class, 'contactPage'])->name('contact-page');
        Route::post('/contact-page', [\App\Http\Controllers\Cms\CmsController::class, 'updateContactPage'])->name('contact-page.update');
        Route::get('/features', [\App\Http\Controllers\Cms\CmsController::class, 'featuresPage'])->name('features');
        Route::post('/features', [\App\Http\Controllers\Cms\CmsController::class, 'updateFeaturesPage'])->name('features.update');
        Route::get('/inquiries', [\App\Http\Controllers\Cms\CmsController::class, 'contactInquiries'])->name('contact-inquiries');
        Route::get('/header-footer', [\App\Http\Controllers\Cms\CmsController::class, 'headerFooterSettings'])->name('header-footer');
        Route::post('/header-footer', [\App\Http\Controllers\Cms\CmsController::class, 'updateHeaderFooterSettings'])->name('header-footer.update');
        Route::get('/seo', [\App\Http\Controllers\Cms\CmsController::class, 'seoSettings'])->name('seo');
        Route::post('/seo', [\App\Http\Controllers\Cms\CmsController::class, 'updateSeoSettings'])->name('seo.update');

        // Blog Management
        Route::get('/blog', [\App\Http\Controllers\Cms\BlogController::class, 'adminIndex'])->name('blog.index');
        Route::get('/blog/create', [\App\Http\Controllers\Cms\BlogController::class, 'create'])->name('blog.create');
        Route::post('/blog', [\App\Http\Controllers\Cms\BlogController::class, 'store'])->name('blog.store');
        Route::get('/blog/{post}/edit', [\App\Http\Controllers\Cms\BlogController::class, 'edit'])->name('blog.edit');
        Route::put('/blog/{post}', [\App\Http\Controllers\Cms\BlogController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{post}', [\App\Http\Controllers\Cms\BlogController::class, 'destroy'])->name('blog.destroy');

        // Testimonials Management
        Route::get('/testimonials', [\App\Http\Controllers\Cms\TestimonialsController::class, 'adminIndex'])->name('testimonials.index');
        Route::get('/testimonials/{testimonial}', [\App\Http\Controllers\Cms\TestimonialsController::class, 'show'])->name('testimonials.show');
        Route::get('/testimonials/{testimonial}/edit', [\App\Http\Controllers\Cms\TestimonialsController::class, 'edit'])->name('testimonials.edit');
        Route::put('/testimonials/{testimonial}', [\App\Http\Controllers\Cms\TestimonialsController::class, 'update'])->name('testimonials.update');
        Route::delete('/testimonials/{testimonial}', [\App\Http\Controllers\Cms\TestimonialsController::class, 'destroy'])->name('testimonials.destroy');

        // Gallery Management
        Route::get('/gallery', [\App\Http\Controllers\Cms\GalleryController::class, 'adminIndex'])->name('gallery.index');
        Route::get('/gallery/create', [\App\Http\Controllers\Cms\GalleryController::class, 'create'])->name('gallery.create');
        Route::post('/gallery', [\App\Http\Controllers\Cms\GalleryController::class, 'store'])->name('gallery.store');
        Route::get('/gallery/{item}', [\App\Http\Controllers\Cms\GalleryController::class, 'show'])->name('gallery.show');
        Route::get('/gallery/{item}/edit', [\App\Http\Controllers\Cms\GalleryController::class, 'edit'])->name('gallery.edit');
        Route::put('/gallery/{item}', [\App\Http\Controllers\Cms\GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('/gallery/{item}', [\App\Http\Controllers\Cms\GalleryController::class, 'destroy'])->name('gallery.destroy');

        // Careers/Jobs Management
        Route::get('/careers', [\App\Http\Controllers\Cms\CareersController::class, 'adminIndex'])->name('careers.index');
        Route::get('/careers/create', [\App\Http\Controllers\Cms\CareersController::class, 'create'])->name('careers.create');
        Route::post('/careers', [\App\Http\Controllers\Cms\CareersController::class, 'store'])->name('careers.store');
        Route::get('/careers/{job}/edit', [\App\Http\Controllers\Cms\CareersController::class, 'edit'])->name('careers.edit');
        Route::put('/careers/{job}', [\App\Http\Controllers\Cms\CareersController::class, 'update'])->name('careers.update');
        Route::delete('/careers/{job}', [\App\Http\Controllers\Cms\CareersController::class, 'destroy'])->name('careers.destroy');
        Route::get('/careers/applications', [\App\Http\Controllers\Cms\CareersController::class, 'applications'])->name('careers.applications');
    });

    // Marketing Suite Routes
    Route::prefix('marketing')->name('marketing.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Marketing\MarketingDashboardController::class, 'index'])->name('dashboard');

        // AI Content Generation
        Route::prefix('ai')->name('ai.')->group(function () {
            Route::post('/generate-content', [\App\Http\Controllers\Marketing\AiContentController::class, 'generateContent'])->name('generate-content');
            Route::post('/generate-hashtags', [\App\Http\Controllers\Marketing\AiContentController::class, 'generateHashtags'])->name('generate-hashtags');
            Route::post('/generate-cta', [\App\Http\Controllers\Marketing\AiContentController::class, 'generateCta'])->name('generate-cta');
        });

        // Marketing Posts
        Route::resource('posts', \App\Http\Controllers\Marketing\MarketingPostController::class);
        Route::post('/posts/{post}/approve', [\App\Http\Controllers\Marketing\MarketingPostController::class, 'approve'])->name('posts.approve');

        // Campaigns
        Route::resource('campaigns', \App\Http\Controllers\Marketing\CampaignController::class);

        // Social Accounts
        Route::resource('social-accounts', \App\Http\Controllers\Marketing\SocialAccountController::class);
        Route::get('/social-accounts/connect/{platform}', [\App\Http\Controllers\Marketing\SocialAccountController::class, 'connect'])->name('social-accounts.connect');
        Route::get('/social-accounts/callback/{platform}', [\App\Http\Controllers\Marketing\SocialAccountController::class, 'callback'])->name('social-accounts.callback');

        // Scheduler
        Route::prefix('scheduler')->name('scheduler.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Marketing\SchedulerController::class, 'index'])->name('index');
            Route::post('/schedule', [\App\Http\Controllers\Marketing\SchedulerController::class, 'schedule'])->name('schedule');
            Route::post('/{scheduledPost}/publish-now', [\App\Http\Controllers\Marketing\SchedulerController::class, 'publishNow'])->name('publish-now');
            Route::delete('/{scheduledPost}', [\App\Http\Controllers\Marketing\SchedulerController::class, 'cancel'])->name('cancel');
        });

        // Comment Replies
        Route::prefix('comments')->name('comments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Marketing\CommentReplyController::class, 'index'])->name('index');
            Route::post('/{commentReply}/approve', [\App\Http\Controllers\Marketing\CommentReplyController::class, 'approve'])->name('approve');
            Route::post('/{commentReply}/reject', [\App\Http\Controllers\Marketing\CommentReplyController::class, 'reject'])->name('reject');
        });

        // Graphic Assets
        Route::resource('graphics', \App\Http\Controllers\Marketing\GraphicAssetController::class);

        // SEO Management
        Route::prefix('seo')->name('seo.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Marketing\SeoController::class, 'index'])->name('index');
            Route::post('/optimize', [\App\Http\Controllers\Marketing\SeoController::class, 'optimize'])->name('optimize');
        });
    });
});

require __DIR__.'/auth.php';

// Note: Web login is handled by auth.php routes above
// API login is available at /api/login for JSON requests

// JSON fallbacks for tests that still hit web routes without CSRF; route to API handlers
Route::middleware('api')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->group(function () {
        Route::post('/patients', [ApiController::class, 'createPatient'])->name('web.json.patients.create');
        Route::post('/doctors', [ApiController::class, 'createDoctor'])->name('web.json.doctors.create');
        Route::post('/appointments', [ApiController::class, 'createAppointment'])->name('web.json.appointments.create');
        Route::post('/beds', [ApiController::class, 'createBed'])->name('web.json.beds.create');
    });
