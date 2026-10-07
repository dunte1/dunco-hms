<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Hms\PatientsController;
use App\Http\Controllers\Hms\AppointmentsController;
use App\Http\Controllers\Hms\ShaController;
use App\Http\Controllers\Hms\ICD10Controller;
use App\Http\Controllers\Hms\QueueManagementController;
use App\Http\Controllers\Hms\DoctorsController;

use App\Http\Controllers\Hms\BillingController;
use App\Http\Controllers\Hms\PharmacyController;
use App\Http\Controllers\Hms\LaboratoryController;
use App\Http\Controllers\Hms\RadiologyController;
use App\Http\Controllers\Hms\InventoryController;
use App\Http\Controllers\Hms\HrController;
use App\Http\Controllers\Hms\SettingsController;
use App\Http\Controllers\Hms\BedTypesController;
use App\Http\Controllers\Hms\BedsController;
use App\Http\Controllers\Hms\OtSchedulingController;
use App\Http\Controllers\Hms\DrugInteractionController;
use App\Http\Controllers\Hms\CssdController;
use App\Http\Controllers\Hms\InstrumentSetController;
use App\Http\Controllers\Hms\CssdCycleController;
use App\Http\Controllers\Hms\SterilizerRunController;
use App\Http\Controllers\Hms\SterilityIndicatorController;
use App\Http\Controllers\Hms\CssdIssueController;
use App\Http\Controllers\Hms\ConsentController;
use App\Http\Controllers\Hms\MrdController;
use App\Http\Controllers\Hms\VaccinationController;
use App\Http\Controllers\Hms\ImmunizationScheduleController;
use App\Http\Controllers\Hms\FpVisitController;
use App\Http\Controllers\Hms\SurveillanceController;
use App\Http\Controllers\Hms\OutbreakController;
use App\Http\Controllers\Hms\MortuaryController;
use App\Http\Controllers\Hms\MortuarySlotController;
use App\Http\Controllers\Hms\BodyIdentificationController;
use App\Http\Controllers\Hms\PostmortemController;
use App\Http\Controllers\Hms\DeathCertificateController;
use App\Http\Controllers\Hms\EquipmentMaintenanceController;
use App\Http\Controllers\Hms\StoreController;
use App\Http\Controllers\Hms\RequisitionController;
use App\Http\Controllers\Hms\StocktakeController;
use App\Http\Controllers\Hms\StockAdjustmentController;
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
use App\Http\Controllers\Hms\NewbornController;
use App\Http\Controllers\Hms\NeonatalAssessmentController;
use App\Http\Controllers\Hms\NicuController;
use App\Http\Controllers\Hms\PhototherapyController;
use App\Http\Controllers\Hms\NeonatalFeedController;
use App\Http\Controllers\Cms\BlogController;
use App\Http\Controllers\Cms\GalleryController;
use App\Http\Controllers\Cms\CareersController;
use App\Http\Controllers\Cms\TestimonialsController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
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
    // Search API for AJAX typeahead dropdowns
    Route::get('/api/patients/search', [\App\Http\Controllers\Hms\SearchApiController::class, 'patients'])->name('api.patients.search');
    Route::get('/api/doctors/search', [\App\Http\Controllers\Hms\SearchApiController::class, 'doctors'])->name('api.doctors.search');
    Route::get('/api/employees/search', [\App\Http\Controllers\Hms\SearchApiController::class, 'employees'])->name('api.employees.search');

    // AI Features
    Route::get('/ai/appointment-suggestions', [\App\Http\Controllers\Ai\AiAssistantController::class, 'appointmentSuggestions'])->name('ai.appointment-suggestions')->middleware('permission:use ai assistant|manage ai suggestions');
    Route::post('/ai/appointment-suggestions/generate', [\App\Http\Controllers\Ai\AiAssistantController::class, 'generateAppointmentSuggestion'])->name('ai.appointment-suggestions.generate')->middleware('permission:use ai assistant|manage ai suggestions');
    Route::get('/ai/diagnosis-suggestions', [\App\Http\Controllers\Ai\AiAssistantController::class, 'diagnosisSuggestions'])->name('ai.diagnosis-suggestions')->middleware('permission:use ai assistant|manage ai suggestions');
    Route::post('/ai/diagnosis-suggestions/generate', [\App\Http\Controllers\Ai\AiAssistantController::class, 'generateDiagnosisSuggestion'])->name('ai.diagnosis-suggestions.generate')->middleware('permission:use ai assistant|manage ai suggestions');
    
    // Elliana D - Virtual Nurse Assistant
    Route::get('/ai/elliana-d', [\App\Http\Controllers\Ai\EllianaDController::class, 'index'])->name('ai.elliana-d')->middleware('permission:use ai assistant|manage ai suggestions');
    Route::post('/ai/elliana-d/chat', [\App\Http\Controllers\Ai\EllianaDController::class, 'chat'])->name('ai.elliana-d.chat')->middleware('permission:use ai assistant|manage ai suggestions');
    Route::get('/ai/elliana-d/history', [\App\Http\Controllers\Ai\EllianaDController::class, 'history'])->name('ai.elliana-d.history')->middleware('permission:use ai assistant|manage ai suggestions');
    
    // Integration Features
    Route::get('/integration/lab-equipment', [\App\Http\Controllers\Integration\LabIntegrationController::class, 'index'])->name('integration.lab-equipment')->middleware('permission:manage lab equipment|manage lab integration');
    Route::post('/integration/lab-equipment', [\App\Http\Controllers\Integration\LabIntegrationController::class, 'createEquipment'])->name('integration.lab-equipment.create')->middleware('permission:manage lab equipment|manage lab integration');
    Route::post('/integration/lab-equipment/{equipment}/test', [\App\Http\Controllers\Integration\LabIntegrationController::class, 'testConnection'])->name('integration.lab-equipment.test')->middleware('permission:manage lab equipment|manage lab integration');
    Route::get('/integration/lab-equipment/{equipment}/results', [\App\Http\Controllers\Integration\LabIntegrationController::class, 'getEquipmentResults'])->name('integration.lab-equipment.results')->middleware('permission:manage lab equipment|manage lab integration');
    Route::post('/integration/lab-equipment/receive-results', [\App\Http\Controllers\Integration\LabIntegrationController::class, 'receiveResults'])->name('integration.lab-equipment.receive')->middleware('permission:manage lab equipment|manage lab integration');
    
    Route::get('/integration/insurance-api', [\App\Http\Controllers\Integration\InsuranceApiController::class, 'index'])->name('integration.insurance-api')->middleware('permission:manage insurance API|manage insurance integration|verify insurance|manage insurance claims');
    Route::post('/integration/insurance/verify', [\App\Http\Controllers\Integration\InsuranceApiController::class, 'verifyInsurance'])->name('integration.insurance.verify')->middleware('permission:manage insurance API|manage insurance integration|verify insurance|manage insurance claims');
    Route::post('/integration/insurance/submit-claim', [\App\Http\Controllers\Integration\InsuranceApiController::class, 'submitClaim'])->name('integration.insurance.submit-claim')->middleware('permission:manage insurance API|manage insurance integration|verify insurance|manage insurance claims');
    Route::post('/integration/insurance/check-eligibility', [\App\Http\Controllers\Integration\InsuranceApiController::class, 'checkEligibility'])->name('integration.insurance.check-eligibility')->middleware('permission:manage insurance API|manage insurance integration|verify insurance|manage insurance claims');
    
    // DHA - Digital Health Superhighway (Client Registry, Facility & Provider Registries, Afyalink)
    Route::middleware('module:dha-integration')->group(function () {
        Route::get('/integration/dha', [\App\Http\Controllers\Integration\DhaIntegrationController::class, 'index'])->name('integration.dha.index');
        Route::post('/integration/dha/client-registry/search', [\App\Http\Controllers\Integration\DhaIntegrationController::class, 'searchClientRegistry'])->name('integration.dha.client-registry.search');
        Route::post('/integration/dha/verify-patient', [\App\Http\Controllers\Integration\DhaIntegrationController::class, 'verifyPatient'])->name('integration.dha.verify');
        Route::post('/integration/dha/facility-lookup', [\App\Http\Controllers\Integration\DhaIntegrationController::class, 'lookupFacility'])->name('integration.dha.facility-lookup');
        Route::post('/integration/dha/provider-search', [\App\Http\Controllers\Integration\DhaIntegrationController::class, 'searchProvider'])->name('integration.dha.provider-search');
        Route::post('/integration/dha/transmit-document', [\App\Http\Controllers\Integration\DhaIntegrationController::class, 'transmitDocument'])->name('integration.dha.transmit-document');
        Route::post('/integration/dha/biometric-verify', [\App\Http\Controllers\Integration\DhaIntegrationController::class, 'verifyBiometric'])->name('integration.dha.biometric-verify');
    });
    
    // Analytics & BI
    Route::get('/analytics/bi-dashboard', [\App\Http\Controllers\Analytics\BiDashboardController::class, 'index'])->name('analytics.bi-dashboard')->middleware('permission:view analytics|view dashboard analytics');
    Route::post('/analytics/generate', [\App\Http\Controllers\Analytics\BiDashboardController::class, 'generateAnalytics'])->name('analytics.generate')->middleware('permission:view analytics|view dashboard analytics');
    Route::get('/analytics/revenue', [\App\Http\Controllers\Analytics\BiDashboardController::class, 'getRevenueAnalytics'])->name('analytics.revenue')->middleware('permission:view analytics|view dashboard analytics');
    Route::get('/analytics/patients', [\App\Http\Controllers\Analytics\BiDashboardController::class, 'getPatientAnalytics'])->name('analytics.patients')->middleware('permission:view analytics|view dashboard analytics');
    Route::get('/analytics/occupancy', [\App\Http\Controllers\Analytics\BiDashboardController::class, 'getOccupancyAnalytics'])->name('analytics.occupancy')->middleware('permission:view analytics|view dashboard analytics');
    
    // Telemedicine
    Route::get('/telemedicine', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'index'])->name('telemedicine.index')->middleware('permission:use telemedicine|manage telemedicine sessions');
    Route::get('/telemedicine/create', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'create'])->name('telemedicine.create')->middleware('permission:use telemedicine|manage telemedicine sessions');
    Route::post('/telemedicine', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'store'])->name('telemedicine.store')->middleware('permission:use telemedicine|manage telemedicine sessions');
    Route::post('/telemedicine/{session}/start', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'startSession'])->name('telemedicine.start')->middleware('permission:use telemedicine|manage telemedicine sessions');
    Route::post('/telemedicine/{session}/end', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'endSession'])->name('telemedicine.end')->middleware('permission:use telemedicine|manage telemedicine sessions');
    Route::get('/telemedicine/{session}/join', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'joinSession'])->name('telemedicine.join')->middleware('permission:use telemedicine|manage telemedicine sessions');
    Route::get('/telemedicine/{session}/details', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'getSessionDetails'])->name('telemedicine.details')->middleware('permission:use telemedicine|manage telemedicine sessions');
    Route::get('/telemedicine/upcoming', [\App\Http\Controllers\Telemedicine\TelemedicineController::class, 'getUpcomingSessions'])->name('telemedicine.upcoming')->middleware('permission:use telemedicine|manage telemedicine sessions');
    Route::post('/telemedicine/{session}/participants', [\App\Http\Controllers\Telemedicine\TeleParticipantController::class, 'store'])->name('telemedicine.participants.store')->middleware('permission:use telemedicine|manage telemedicine sessions');
    
    // Drug Interactions & Allergies
    Route::get('/drug-interactions', [DrugInteractionController::class, 'index'])->name('drug-interactions.index')->middleware('permission:view prescriptions|dispense medicines|manage medicine inventory');
    Route::get('/drug-interactions/create', [DrugInteractionController::class, 'create'])->name('drug-interactions.create')->middleware('permission:view prescriptions|dispense medicines|manage medicine inventory');
    Route::post('/drug-interactions', [DrugInteractionController::class, 'store'])->name('drug-interactions.store')->middleware('permission:view prescriptions|dispense medicines|manage medicine inventory');
    Route::get('/drug-interactions/{drugInteraction}', [DrugInteractionController::class, 'show'])->name('drug-interactions.show')->middleware('permission:view prescriptions|dispense medicines|manage medicine inventory');
    Route::get('/drug-interactions/{drugInteraction}/edit', [DrugInteractionController::class, 'edit'])->name('drug-interactions.edit')->middleware('permission:view prescriptions|dispense medicines|manage medicine inventory');
    Route::put('/drug-interactions/{drugInteraction}', [DrugInteractionController::class, 'update'])->name('drug-interactions.update')->middleware('permission:view prescriptions|dispense medicines|manage medicine inventory');
    Route::delete('/drug-interactions/{drugInteraction}', [DrugInteractionController::class, 'destroy'])->name('drug-interactions.destroy')->middleware('permission:view prescriptions|dispense medicines|manage medicine inventory');
    Route::get('/patient-allergies/{patientId}', [DrugInteractionController::class, 'patientAllergies'])->name('patient-allergies')->middleware('permission:view prescriptions|dispense medicines|manage medicine inventory|view patients');
    Route::post('/patient-allergies', [DrugInteractionController::class, 'storeAllergy'])->name('patient-allergies.store')->middleware('permission:view prescriptions|dispense medicines|manage medicine inventory|view patients');
    Route::delete('/patient-allergies/{allergy}', [DrugInteractionController::class, 'destroyAllergy'])->name('patient-allergies.destroy')->middleware('permission:view prescriptions|dispense medicines|manage medicine inventory|view patients');
    
    // CSSD (Central Sterile Services Department)
    Route::get('/cssd', [CssdController::class, 'index'])->name('cssd.index')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
    Route::get('/cssd/instruments/create', [CssdController::class, 'createInstrument'])->name('cssd.instrument-create')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
    Route::post('/cssd/instruments', [CssdController::class, 'storeInstrument'])->name('cssd.instrument-store')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
    Route::get('/cssd/instruments/{instrument}', [CssdController::class, 'showInstrument'])->name('cssd.instrument-show')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
    Route::get('/cssd/instruments/{instrument}/edit', [CssdController::class, 'editInstrument'])->name('cssd.instrument-edit')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
    Route::put('/cssd/instruments/{instrument}', [CssdController::class, 'updateInstrumentFull'])->name('cssd.instrument-update')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
    Route::delete('/cssd/instruments/{instrument}', [CssdController::class, 'destroyInstrument'])->name('cssd.instrument-destroy')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
    Route::post('/cssd/batches', [CssdController::class, 'storeBatch'])->name('cssd.batch-store')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
    Route::post('/cssd/batches/{batch}/complete', [CssdController::class, 'completeBatch'])->name('cssd.batch-complete')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');

    // Consent Management
    Route::get('/consent', [ConsentController::class, 'index'])->name('consent.index')->middleware('permission:manage record requests|upload documents|view patients');
    Route::get('/consent/create', [ConsentController::class, 'create'])->name('consent.create')->middleware('permission:manage record requests|upload documents|view patients');
    Route::post('/consent', [ConsentController::class, 'store'])->name('consent.store')->middleware('permission:manage record requests|upload documents|view patients');
    Route::get('/consent/{consent}', [ConsentController::class, 'show'])->name('consent.show')->middleware('permission:manage record requests|upload documents|view patients');
    Route::get('/consent/{consent}/edit', [ConsentController::class, 'edit'])->name('consent.edit')->middleware('permission:manage record requests|upload documents|view patients');
    Route::put('/consent/{consent}', [ConsentController::class, 'update'])->name('consent.update')->middleware('permission:manage record requests|upload documents|view patients');
    Route::post('/consent/{consent}/sign', [ConsentController::class, 'sign'])->name('consent.sign')->middleware('permission:manage record requests|upload documents|view patients');
    Route::delete('/consent/{consent}', [ConsentController::class, 'destroy'])->name('consent.destroy')->middleware('permission:manage record requests|upload documents|view patients');
    
    // MRD (Medical Records Department)
    Route::get('/mrd', [MrdController::class, 'index'])->name('mrd.index')->middleware('permission:manage record requests|upload scanned documents|upload documents');
    Route::get('/mrd/create', [MrdController::class, 'create'])->name('mrd.create')->middleware('permission:manage record requests|upload scanned documents|upload documents');
    Route::post('/mrd', [MrdController::class, 'store'])->name('mrd.store')->middleware('permission:manage record requests|upload scanned documents|upload documents');
    Route::get('/mrd/{file}', [MrdController::class, 'show'])->name('mrd.show')->middleware('permission:manage record requests|upload scanned documents|upload documents');
    Route::get('/mrd/{file}/edit', [MrdController::class, 'edit'])->name('mrd.edit')->middleware('permission:manage record requests|upload scanned documents|upload documents');
    Route::put('/mrd/{file}', [MrdController::class, 'update'])->name('mrd.update')->middleware('permission:manage record requests|upload scanned documents|upload documents');
    Route::delete('/mrd/{file}', [MrdController::class, 'destroy'])->name('mrd.destroy')->middleware('permission:manage record requests|upload scanned documents|upload documents');
    Route::post('/mrd/{file}/issue', [MrdController::class, 'issue'])->name('mrd.issue')->middleware('permission:manage record requests|upload scanned documents|upload documents');
    Route::post('/mrd/{file}/return', [MrdController::class, 'return'])->name('mrd.return')->middleware('permission:manage record requests|upload scanned documents|upload documents');
    
    // Medical Records (G057-G058)
    Route::post('/records/requests', [\App\Http\Controllers\Hms\RecordRequestController::class, 'store'])->name('records.requests.store')->middleware('permission:manage record requests|manage icd coding|upload scanned documents');
    Route::post('/records/requests/{request}/approve', [\App\Http\Controllers\Hms\RecordRequestController::class, 'approve'])->name('records.requests.approve')->middleware('permission:manage record requests|manage icd coding|upload scanned documents');
    Route::post('/records/requests/{request}/release', [\App\Http\Controllers\Hms\RecordRequestController::class, 'release'])->name('records.requests.release')->middleware('permission:manage record requests|manage icd coding|upload scanned documents');
    Route::post('/records/scan', [\App\Http\Controllers\Hms\ScannedDocumentController::class, 'store'])->name('records.scan.store')->middleware('permission:manage record requests|manage icd coding|upload scanned documents');
    Route::post('/records/coding', [\App\Http\Controllers\Hms\IcdCodingController::class, 'store'])->name('records.coding.store')->middleware('permission:manage record requests|manage icd coding|upload scanned documents');
    Route::post('/records/coding/{coding}/approve', [\App\Http\Controllers\Hms\IcdCodingController::class, 'approve'])->name('records.coding.approve')->middleware('permission:manage record requests|manage icd coding|upload scanned documents');
    Route::post('/reports/khis', [\App\Http\Controllers\Hms\KhisReportController::class, 'store'])->name('reports.khis.store')->middleware('permission:manage khis reports|view reports');
    Route::post('/reports/khis/{report}/submit', [\App\Http\Controllers\Hms\KhisReportController::class, 'submit'])->name('reports.khis.submit')->middleware('permission:manage khis reports|view reports');
    Route::post('/records/data-quality', [\App\Http\Controllers\Hms\DataQualityController::class, 'store'])->name('records.data-quality.store')->middleware('permission:manage record requests|manage icd coding|upload scanned documents');
    Route::post('/records/data-quality/{issue}/resolve', [\App\Http\Controllers\Hms\DataQualityController::class, 'resolve'])->name('records.data-quality.resolve')->middleware('permission:manage record requests|manage icd coding|upload scanned documents');
    
    // Vaccination Management
    Route::get('/vaccination', [VaccinationController::class, 'index'])->name('vaccination.index')->middleware('permission:manage immunization schedules');
    Route::post('/vaccination/vaccines', [VaccinationController::class, 'storeVaccine'])->name('vaccination.vaccine-store')->middleware('permission:manage immunization schedules');
    Route::get('/vaccination/vaccines/{vaccine}', [VaccinationController::class, 'showVaccine'])->name('vaccination.vaccine-show')->middleware('permission:manage immunization schedules');
    Route::get('/vaccination/vaccines/{vaccine}/edit', [VaccinationController::class, 'editVaccine'])->name('vaccination.vaccine-edit')->middleware('permission:manage immunization schedules');
    Route::put('/vaccination/vaccines/{vaccine}', [VaccinationController::class, 'updateVaccine'])->name('vaccination.vaccine-update')->middleware('permission:manage immunization schedules');
    Route::delete('/vaccination/vaccines/{vaccine}', [VaccinationController::class, 'destroyVaccine'])->name('vaccination.vaccine-destroy')->middleware('permission:manage immunization schedules');
    Route::get('/vaccination/administer', [VaccinationController::class, 'administer'])->name('vaccination.administer')->middleware('permission:manage immunization schedules');
    Route::post('/vaccination/administer', [VaccinationController::class, 'storeAdministration'])->name('vaccination.administer-store')->middleware('permission:manage immunization schedules');
    
    // Public Health (M21)
    Route::get('/public-health/immunizations/schedule', [ImmunizationScheduleController::class, 'index'])->name('public-health.immunizations.schedule')->middleware('permission:manage immunization schedules|manage family planning visits|manage surveillance cases|manage outbreak events|manage notifiable disease reports');
    Route::post('/public-health/immunizations/schedule/{schedule}/complete', [ImmunizationScheduleController::class, 'complete'])->name('public-health.immunizations.complete')->middleware('permission:manage immunization schedules|manage family planning visits|manage surveillance cases|manage outbreak events|manage notifiable disease reports');
    Route::post('/public-health/fp', [FpVisitController::class, 'store'])->name('public-health.fp.store')->middleware('permission:manage immunization schedules|manage family planning visits|manage surveillance cases|manage outbreak events|manage notifiable disease reports');
    Route::get('/public-health/fp', [FpVisitController::class, 'index'])->name('public-health.fp.index')->middleware('permission:manage immunization schedules|manage family planning visits|manage surveillance cases|manage outbreak events|manage notifiable disease reports');
    Route::post('/public-health/surveillance', [SurveillanceController::class, 'store'])->name('public-health.surveillance.store')->middleware('permission:manage immunization schedules|manage family planning visits|manage surveillance cases|manage outbreak events|manage notifiable disease reports');
    Route::get('/public-health/surveillance', [SurveillanceController::class, 'index'])->name('public-health.surveillance.index')->middleware('permission:manage immunization schedules|manage family planning visits|manage surveillance cases|manage outbreak events|manage notifiable disease reports');
    Route::get('/public-health/outbreaks', [OutbreakController::class, 'index'])->name('public-health.outbreaks.index')->middleware('permission:manage immunization schedules|manage family planning visits|manage surveillance cases|manage outbreak events|manage notifiable disease reports');
    Route::post('/public-health/outbreaks', [OutbreakController::class, 'store'])->name('public-health.outbreaks.store')->middleware('permission:manage immunization schedules|manage family planning visits|manage surveillance cases|manage outbreak events|manage notifiable disease reports');
    Route::put('/public-health/outbreaks/{outbreak}', [OutbreakController::class, 'update'])->name('public-health.outbreaks.update')->middleware('permission:manage immunization schedules|manage family planning visits|manage surveillance cases|manage outbreak events|manage notifiable disease reports');
    
    // Mortuary Management
    Route::get('/mortuary', [MortuaryController::class, 'index'])->name('mortuary.index')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::post('/mortuary', [MortuaryController::class, 'store'])->name('mortuary.store')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::get('/mortuary/{record}', [MortuaryController::class, 'show'])->name('mortuary.show')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::get('/mortuary/{record}/edit', [MortuaryController::class, 'edit'])->name('mortuary.edit')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::put('/mortuary/{record}', [MortuaryController::class, 'update'])->name('mortuary.update')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::delete('/mortuary/{record}', [MortuaryController::class, 'destroy'])->name('mortuary.destroy')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::post('/mortuary/{record}/release', [MortuaryController::class, 'release'])->name('mortuary.release')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::post('/mortuary/{record}/slots', [MortuarySlotController::class, 'assign'])->name('mortuary.slots.assign')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::post('/mortuary/slots/{slot}/release', [MortuarySlotController::class, 'release'])->name('mortuary.slots.release')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::post('/mortuary/{record}/identification', [BodyIdentificationController::class, 'store'])->name('mortuary.identification.store')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::post('/mortuary/{record}/postmortem', [PostmortemController::class, 'store'])->name('mortuary.postmortem.store')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::post('/mortuary/postmortems/{postmortem}/complete', [PostmortemController::class, 'complete'])->name('mortuary.postmortem.complete')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    Route::post('/mortuary/{record}/death-certificate', [DeathCertificateController::class, 'store'])->name('mortuary.death-certificate.store')->middleware('permission:manage mortuary records|manage mortuary slots|manage body identifications|manage postmortems|issue death certificates');
    
    // Equipment Maintenance (CMMS)
    Route::get('/equipment', [EquipmentMaintenanceController::class, 'index'])->name('equipment.index')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::post('/equipment', [EquipmentMaintenanceController::class, 'store'])->name('equipment.store')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::get('/equipment/{equipment}', [EquipmentMaintenanceController::class, 'show'])->name('equipment.show')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::post('/equipment/{equipment}/maintenance', [EquipmentMaintenanceController::class, 'logMaintenance'])->name('equipment.maintenance')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::put('/equipment/{equipment}/status', [EquipmentMaintenanceController::class, 'updateStatus'])->name('equipment.status')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');

    // Maintenance Requests, Work Orders & Calibration (G050-G051)
    Route::get('/maintenance/requests', [\App\Http\Controllers\Hms\MaintenanceRequestController::class, 'index'])->name('maintenance.requests.index')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::post('/maintenance/requests', [\App\Http\Controllers\Hms\MaintenanceRequestController::class, 'store'])->name('maintenance.requests.store')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::post('/maintenance/work-orders', [\App\Http\Controllers\Hms\WorkOrderController::class, 'store'])->name('maintenance.work-orders.store')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::post('/maintenance/work-orders/{order}/complete', [\App\Http\Controllers\Hms\WorkOrderController::class, 'complete'])->name('maintenance.work-orders.complete')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::get('/maintenance/calibrations', [\App\Http\Controllers\Hms\CalibrationController::class, 'index'])->name('maintenance.calibrations.index')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::post('/maintenance/calibrations', [\App\Http\Controllers\Hms\CalibrationController::class, 'store'])->name('maintenance.calibrations.store')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');

    // Asset Register (G050-G051)
    Route::get('/assets', [\App\Http\Controllers\Hms\AssetController::class, 'index'])->name('assets.index')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::post('/assets', [\App\Http\Controllers\Hms\AssetController::class, 'store'])->name('assets.store')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::post('/assets/{asset}/transfer', [\App\Http\Controllers\Hms\AssetController::class, 'transfer'])->name('assets.transfer')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    Route::post('/assets/{asset}/dispose', [\App\Http\Controllers\Hms\AssetController::class, 'dispose'])->name('assets.dispose')->middleware('permission:manage maintenance requests|manage work orders|manage calibrations|manage assets|transfer assets|dispose assets');
    
    // RFID Management
    Route::get('/rfid', [\App\Http\Controllers\Rfid\RfidController::class, 'index'])->name('rfid.index')->middleware('permission:manage rfid tags');
    Route::get('/rfid/create', [\App\Http\Controllers\Rfid\RfidController::class, 'create'])->name('rfid.create')->middleware('permission:manage rfid tags');
    Route::post('/rfid', [\App\Http\Controllers\Rfid\RfidController::class, 'store'])->name('rfid.store')->middleware('permission:manage rfid tags');
    Route::post('/rfid/scan', [\App\Http\Controllers\Rfid\RfidController::class, 'scan'])->name('rfid.scan')->middleware('permission:manage rfid tags');
    Route::get('/rfid/{tagId}/info', [\App\Http\Controllers\Rfid\RfidController::class, 'getTagInfo'])->name('rfid.info')->middleware('permission:manage rfid tags');
    Route::post('/rfid/{tag}/update-status', [\App\Http\Controllers\Rfid\RfidController::class, 'updateStatus'])->name('rfid.update-status')->middleware('permission:manage rfid tags');
    Route::get('/rfid/{tag}/history', [\App\Http\Controllers\Rfid\RfidController::class, 'getLocationHistory'])->name('rfid.history')->middleware('permission:manage rfid tags');
    Route::get('/rfid/active', [\App\Http\Controllers\Rfid\RfidController::class, 'getActiveTags'])->name('rfid.active')->middleware('permission:manage rfid tags');
    Route::get('/rfid/location/{location}', [\App\Http\Controllers\Rfid\RfidController::class, 'getTagsByLocation'])->name('rfid.by-location')->middleware('permission:manage rfid tags');
    Route::post('/rfid/report', [\App\Http\Controllers\Rfid\RfidController::class, 'generateReport'])->name('rfid.report')->middleware('permission:manage rfid tags');
    Route::post('/rfid/bulk-update', [\App\Http\Controllers\Rfid\RfidController::class, 'bulkUpdate'])->name('rfid.bulk-update')->middleware('permission:manage rfid tags');
    
    // IoT Bed Monitoring
    Route::get('/iot/bed-monitoring', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'index'])->name('iot.bed-monitoring')->middleware('permission:monitor iot sensors');
    Route::get('/iot/sensor/create', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'create'])->name('iot.sensor.create')->middleware('permission:monitor iot sensors');
    Route::post('/iot/sensor', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'store'])->name('iot.sensor.store')->middleware('permission:monitor iot sensors');
    Route::post('/iot/sensor/receive-data', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'receiveSensorData'])->name('iot.sensor.receive-data')->middleware('permission:monitor iot sensors');
    Route::get('/iot/sensor/{sensor}/data', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'getSensorData'])->name('iot.sensor.data')->middleware('permission:monitor iot sensors');
    Route::get('/iot/bed/{bed}/status', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'getBedStatus'])->name('iot.bed.status')->middleware('permission:monitor iot sensors');
    Route::get('/iot/bed-occupancy-map', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'getOccupancyMap'])->name('iot.bed-occupancy-map')->middleware('permission:monitor iot sensors');
    Route::get('/iot/alerts', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'getAlerts'])->name('iot.alerts')->middleware('permission:monitor iot sensors');
    Route::post('/iot/sensor/{sensor}/acknowledge', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'acknowledgeAlert'])->name('iot.sensor.acknowledge')->middleware('permission:monitor iot sensors');
    Route::get('/iot/sensor/{sensor}/history', [\App\Http\Controllers\Iot\IotBedMonitoringController::class, 'getVitalSignsHistory'])->name('iot.sensor.history')->middleware('permission:monitor iot sensors');
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

    // Portal Dependants & Messaging (G079-G082)
    Route::get('/dependants', [\App\Http\Controllers\PatientPortal\PortalDependantController::class, 'index'])->name('patient-portal.dependants.index');
    Route::post('/dependants', [\App\Http\Controllers\PatientPortal\PortalDependantController::class, 'store'])->name('patient-portal.dependants.store');
    Route::post('/messages', [\App\Http\Controllers\PatientPortal\PortalMessageController::class, 'send'])->name('patient-portal.messages.send');
    Route::get('/messages', [\App\Http\Controllers\PatientPortal\PortalMessageController::class, 'inbox'])->name('patient-portal.messages.inbox');
    Route::post('/messages/{message}/read', [\App\Http\Controllers\PatientPortal\PortalMessageController::class, 'markRead'])->name('patient-portal.messages.mark-read');
});

    // Security & Biometric Routes
Route::middleware(['auth', 'permission:manage security incidents|manage lost found items|manage access events|manage visitor passes'])->prefix('hms/security')->group(function () {
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

    // Security Incidents (G054-G055)
    Route::get('/incidents', [\App\Http\Controllers\Security\SecurityIncidentController::class, 'index'])->name('incidents.index');
    Route::post('/incidents', [\App\Http\Controllers\Security\SecurityIncidentController::class, 'store'])->name('incidents.store');
    Route::post('/incidents/{incident}/resolve', [\App\Http\Controllers\Security\SecurityIncidentController::class, 'resolve'])->name('incidents.resolve');

    // Lost & Found (G054-G055)
    Route::get('/lost-found', [\App\Http\Controllers\Security\LostFoundController::class, 'index'])->name('lost-found.index');
    Route::post('/lost-found', [\App\Http\Controllers\Security\LostFoundController::class, 'store'])->name('lost-found.store');
    Route::post('/lost-found/{item}/claim', [\App\Http\Controllers\Security\LostFoundController::class, 'claim'])->name('lost-found.claim');

    // Access Events (G054-G055)
    Route::get('/access-events', [\App\Http\Controllers\Security\AccessEventController::class, 'index'])->name('access-events.index');
    Route::post('/access-events', [\App\Http\Controllers\Security\AccessEventController::class, 'store'])->name('access-events.store');

    // Vehicle Access (G054-G055)
    Route::get('/vehicles', [\App\Http\Controllers\Security\VehicleAccessController::class, 'index'])->name('vehicles.index');
    Route::post('/vehicles', [\App\Http\Controllers\Security\VehicleAccessController::class, 'store'])->name('vehicles.store');
    Route::post('/vehicles/{vehicle}/depart', [\App\Http\Controllers\Security\VehicleAccessController::class, 'recordDeparture'])->name('vehicles.depart');
});

Route::get('/dashboard', [\App\Http\Controllers\Hms\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Clinician worklist (reception → triage → doctor → pharmacy/lab handoff)
Route::get('/hms/worklist', [\App\Http\Controllers\Hms\ClinicianWorklistController::class, 'index'])
    ->middleware(['auth', 'verified', 'permission:view patients|view appointments|create prescriptions|add test requests|view test results|manage queue'])
    ->name('hms.worklist');

// Role Management Routes ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â requires role management permissions
Route::prefix('admin')->middleware(['auth', 'permission:manage roles|manage permissions'])->group(function () {
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
        // CSSD Completion (G047-G048)
        Route::get('/cssd/instrument-sets', [InstrumentSetController::class, 'index'])->name('cssd.instrument-sets.index')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
        Route::post('/cssd/instrument-sets', [InstrumentSetController::class, 'store'])->name('cssd.instrument-sets.store')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
        Route::post('/cssd/cycles', [CssdCycleController::class, 'store'])->name('cssd.cycles.store')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
        Route::post('/cssd/cycles/{cycle}/complete', [CssdCycleController::class, 'complete'])->name('cssd.cycles.complete')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
        Route::post('/cssd/sterilizer-runs', [SterilizerRunController::class, 'store'])->name('cssd.sterilizer-runs.store')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
        Route::post('/cssd/sterilizer-runs/{run}/indicators', [SterilityIndicatorController::class, 'store'])->name('cssd.sterilizer-runs.indicators.store')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
        Route::post('/cssd/issues', [CssdIssueController::class, 'store'])->name('cssd.issues.store')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');
        Route::post('/cssd/issues/{issue}/return', [CssdIssueController::class, 'returnSet'])->name('cssd.issues.return')->middleware('permission:manage cssd instruments|manage cssd cycles|manage sterilizer runs|manage cssd issues|manage cssd returns|record sterility indicators');

        // Patient management ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â requires patient-related permissions
        Route::middleware('permission:view patients|add patients|edit patients')->group(function () {
            Route::get('/patients', [PatientsController::class, 'index'])->name('patients.index');
            Route::get('/patients/create', [PatientsController::class, 'create'])->name('patients.create');
            Route::post('/patients', [PatientsController::class, 'store'])->name('patients.store');
            Route::get('/patients/{patient}/receipt', [\App\Http\Controllers\Hms\PatientReceiptController::class, 'show'])->name('patients.receipt');
            Route::post('/patients/emergency', [PatientsController::class, 'emergencyRegistration'])->name('patients.emergency');
            Route::post('/patients/{patient}/merge', [PatientsController::class, 'merge'])->name('patients.merge');
            Route::get('/patients/{patient}', [PatientsController::class, 'show'])->name('patients.show');
            Route::get('/patients/{patient}/edit', [PatientsController::class, 'edit'])->name('patients.edit');
            Route::put('/patients/{patient}', [PatientsController::class, 'update'])->name('patients.update');
            Route::delete('/patients/{patient}', [PatientsController::class, 'destroy'])->name('patients.destroy');
        });

        // Paediatrics (M08)
        Route::post('/paediatrics/growth', [\App\Http\Controllers\Hms\GrowthMeasurementController::class, 'store'])->name('paediatrics.growth.store');
        Route::get('/paediatrics/growth/{patient}/chart', [\App\Http\Controllers\Hms\GrowthMeasurementController::class, 'chart'])->name('paediatrics.growth.chart');
        Route::post('/paediatrics/assessments', [\App\Http\Controllers\Hms\DevelopmentalAssessmentController::class, 'store'])->name('paediatrics.assessments.store');
        Route::get('/paediatrics/immunizations', [\App\Http\Controllers\Hms\PaediatricImmunizationController::class, 'index'])->name('paediatrics.immunizations.index');
        Route::post('/paediatrics/immunizations/{schedule}/complete', [\App\Http\Controllers\Hms\PaediatricImmunizationController::class, 'complete'])->name('paediatrics.immunizations.complete');
        Route::post('/paediatrics/child-protection', [\App\Http\Controllers\Hms\ChildProtectionController::class, 'store'])->name('paediatrics.child-protection.store');
        Route::put('/paediatrics/child-protection/{case}', [\App\Http\Controllers\Hms\ChildProtectionController::class, 'update'])->name('paediatrics.child-protection.update');
        Route::post('/paediatrics/child-protection/{case}/close', [\App\Http\Controllers\Hms\ChildProtectionController::class, 'close'])->name('paediatrics.child-protection.close');

        // Appointments ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â requires appointment-related permissions
        Route::middleware('permission:create appointments|manage appointments|view appointments|manage queue')->group(function () {
            Route::get('/appointments', [AppointmentsController::class, 'index'])->name('appointments.index');
            Route::get('/appointments/create', [AppointmentsController::class, 'create'])->name('appointments.create');
            Route::post('/appointments', [AppointmentsController::class, 'store'])->name('appointments.store');
            Route::get('/appointments/{appointment}', [AppointmentsController::class, 'show'])->name('appointments.show');
            Route::get('/appointments/{appointment}/edit', [AppointmentsController::class, 'edit'])->name('appointments.edit');
            Route::put('/appointments/{appointment}', [AppointmentsController::class, 'update'])->name('appointments.update');
            Route::delete('/appointments/{appointment}', [AppointmentsController::class, 'destroy'])->name('appointments.destroy');
        });

        // SHA/SHIF Module Routes
        Route::middleware('module:sha-shif')->group(function () {
            Route::get('/sha', [ShaController::class, 'index'])->name('sha.index');
            Route::get('/sha/members', [ShaController::class, 'members'])->name('sha.members');
            Route::post('/sha/verify', [ShaController::class, 'verify'])->name('sha.verify');
            Route::post('/sha/members', [ShaController::class, 'storeMember'])->name('sha.member.store');
            Route::get('/sha/members/{member}', [ShaController::class, 'memberShow'])->name('sha.member.show');
            Route::get('/sha/authorizations', [ShaController::class, 'authorizations'])->name('sha.authorizations');
            Route::post('/sha/authorizations', [ShaController::class, 'requestAuthorization'])->name('sha.authorization.request');
            Route::get('/sha/authorizations/{authorization}', [ShaController::class, 'authorizationShow'])->name('sha.authorization.show');
            Route::get('/sha/providers', [ShaController::class, 'providers'])->name('sha.providers');
            Route::post('/sha/providers', [ShaController::class, 'storeProvider'])->name('sha.provider.store');
            Route::put('/sha/providers/{provider}', [ShaController::class, 'updateProvider'])->name('sha.provider.update');
            Route::get('/sha/service-codes', [ShaController::class, 'serviceCodes'])->name('sha.service-codes');
        });

        // ICD-10 Module Routes
        Route::get('/icd10', [ICD10Controller::class, 'index'])->name('icd10.index');
        Route::get('/icd10/create', [ICD10Controller::class, 'create'])->name('icd10.create');
        Route::post('/icd10', [ICD10Controller::class, 'store'])->name('icd10.store');
        Route::get('/icd10/{code}', [ICD10Controller::class, 'show'])->name('icd10.show');
        Route::get('/icd10/{code}/edit', [ICD10Controller::class, 'edit'])->name('icd10.edit');
        Route::put('/icd10/{code}', [ICD10Controller::class, 'update'])->name('icd10.update');
        Route::delete('/icd10/{code}', [ICD10Controller::class, 'destroy'])->name('icd10.destroy');
        
        // Queue Management ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â requires appointment permissions
        Route::middleware('permission:create appointments|manage appointments|view appointments|manage queue')->group(function () {
            Route::get('/queue', [QueueManagementController::class, 'index'])->name('queue.index');
            Route::get('/queue/create', [QueueManagementController::class, 'create'])->name('queue.create');
            Route::post('/queue', [QueueManagementController::class, 'store'])->name('queue.store');
            Route::get('/queue/display-board', [QueueManagementController::class, 'displayBoard'])->name('queue.display-board');
            Route::get('/queue/kiosk', [QueueManagementController::class, 'kioskMode'])->name('queue.kiosk');
            Route::get('/queue/smart-display', [QueueManagementController::class, 'smartDisplay'])->name('queue.smart-display');
            Route::get('/queue/current', [QueueManagementController::class, 'getCurrentQueues'])->name('queue.current');
            Route::get('/queue/token-generation', [QueueManagementController::class, 'tokenGeneration'])->name('queue.token-generation');
            Route::post('/queue/generate-token', [QueueManagementController::class, 'generateToken'])->name('queue.generate-token');
            Route::get('/queue/{queue}', [QueueManagementController::class, 'show'])->name('queue.show');
            Route::get('/queue/{queue}/edit', [QueueManagementController::class, 'edit'])->name('queue.edit');
            Route::put('/queue/{queue}', [QueueManagementController::class, 'update'])->name('queue.update');
            Route::post('/queue/{queue}/call', [QueueManagementController::class, 'callQueue'])->name('queue.call');
            Route::post('/queue/{queue}/start-service', [QueueManagementController::class, 'startService'])->name('queue.start-service');
            Route::post('/queue/{queue}/complete', [QueueManagementController::class, 'completeQueue'])->name('queue.complete');
            Route::delete('/queue/{queue}/cancel', [QueueManagementController::class, 'cancelQueue'])->name('queue.cancel');
            Route::delete('/queue/{queue}', [QueueManagementController::class, 'destroy'])->name('queue.destroy');
            Route::get('/queue/token-success/{queue}', [QueueManagementController::class, 'tokenSuccess'])->name('queue.token-success');
        });
        
        // Visitor Management Routes
        Route::get('/visitors', [\App\Http\Controllers\Hms\VisitorController::class, 'index'])->name('visitors.index');
        Route::get('/visitors/create', [\App\Http\Controllers\Hms\VisitorController::class, 'create'])->name('visitors.create');
        Route::post('/visitors', [\App\Http\Controllers\Hms\VisitorController::class, 'store'])->name('visitors.store');
        Route::get('/visitors/analytics', [\App\Http\Controllers\Hms\VisitorController::class, 'analytics'])->name('visitors.analytics');
        Route::get('/visitors/{visitor}', [\App\Http\Controllers\Hms\VisitorController::class, 'show'])->name('visitors.show');
        Route::get('/visitors/{visitor}/edit', [\App\Http\Controllers\Hms\VisitorController::class, 'edit'])->name('visitors.edit');
        Route::put('/visitors/{visitor}', [\App\Http\Controllers\Hms\VisitorController::class, 'update'])->name('visitors.update');
        Route::post('/visitors/{visitor}/check-out', [\App\Http\Controllers\Hms\VisitorController::class, 'checkOut'])->name('visitors.check-out');
        Route::get('/visitors/{visitor}/badge', [\App\Http\Controllers\Hms\VisitorController::class, 'printBadge'])->name('visitors.badge');
        Route::delete('/visitors/{visitor}', [\App\Http\Controllers\Hms\VisitorController::class, 'destroy'])->name('visitors.destroy');
        
        Route::get('/doctors', [DoctorsController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/create', [DoctorsController::class, 'create'])->name('doctors.create');
        Route::post('/doctors', [DoctorsController::class, 'store'])->name('doctors.store');
        // Doctor Departments (must come before /doctors/{doctor})
        Route::get('/doctors/departments', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'index'])->name('doctors.departments.index');
        Route::get('/doctors/departments/create', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'create'])->name('doctors.departments.create');
        Route::post('/doctors/departments', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'store'])->name('doctors.departments.store');
        Route::get('/doctors/departments/{department}', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'show'])->name('doctors.departments.show');
        Route::get('/doctors/departments/{department}/edit', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'edit'])->name('doctors.departments.edit');
        Route::put('/doctors/departments/{department}', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'update'])->name('doctors.departments.update');
        Route::delete('/doctors/departments/{department}', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'destroy'])->name('doctors.departments.destroy');
        Route::get('/doctor-departments/{department}', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'show'])->name('doctor-departments.show');
        Route::get('/doctor-departments/{department}/edit', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'edit'])->name('doctor-departments.edit');
        Route::put('/doctor-departments/{department}', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'update'])->name('doctor-departments.update');
        Route::delete('/doctor-departments/{department}', [\App\Http\Controllers\Hms\DoctorDepartmentsController::class, 'destroy'])->name('doctor-departments.destroy');
        Route::get('/doctors/{doctor}', [DoctorsController::class, 'show'])->name('doctors.show');
        Route::get('/doctors/{doctor}/edit', [DoctorsController::class, 'edit'])->name('doctors.edit');
        Route::put('/doctors/{doctor}', [DoctorsController::class, 'update'])->name('doctors.update');
        Route::delete('/doctors/{doctor}', [DoctorsController::class, 'destroy'])->name('doctors.destroy');
        // Route for admissions is now handled by IPD (In-Patient Department) routes below
        Route::get('/billing', [BillingController::class, 'index'])->name('billing.index')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::get('/pharmacy', [PharmacyController::class, 'index'])->name('pharmacy.index');
        Route::get('/laboratory', [LaboratoryController::class, 'index'])->name('laboratory.index');
        Route::get('/radiology', [RadiologyController::class, 'index'])->name('radiology.index');
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        
        // Store Management (Multi-Store Inventory)
        Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
        Route::get('/stores/create', [StoreController::class, 'create'])->name('stores.create');
        Route::post('/stores', [StoreController::class, 'store'])->name('stores.store');
        Route::get('/stores/transfer', [StoreController::class, 'transfer'])->name('stores.transfer');
        Route::post('/stores/transfer', [StoreController::class, 'storeTransfer'])->name('stores.transfer-store');
        Route::get('/stores/issues', [\App\Http\Controllers\Hms\StockIssueController::class, 'index'])->name('stores.issues.index');
        Route::post('/stores/issues', [\App\Http\Controllers\Hms\StockIssueController::class, 'store'])->name('stores.issues.store');
        Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');
        Route::get('/stores/{store}/edit', [StoreController::class, 'edit'])->name('stores.edit');
        Route::put('/stores/{store}', [StoreController::class, 'update'])->name('stores.update');
        Route::delete('/stores/{store}', [StoreController::class, 'destroy'])->name('stores.destroy');
        Route::get('/stores/{store}/stock', [StoreController::class, 'stock'])->name('stores.stock');
        Route::post('/stores/{store}/adjust-stock', [StoreController::class, 'adjustStock'])->name('stores.adjust-stock');
        Route::get('/stores/{store}/stock', [StoreController::class, 'stock'])->name('stores.stock');
        Route::get('/stores/{store}/batches', [StoreController::class, 'batches'])->name('stores.batches');
        Route::post('/stores/{store}/batches', [StoreController::class, 'storeBatch'])->name('stores.batches.store');
        Route::get('/stores/{store}/reports', [StoreController::class, 'reports'])->name('stores.reports');

        // Requisitions
        Route::get('/requisitions', [\App\Http\Controllers\Hms\RequisitionController::class, 'index'])->name('requisitions.index');
        Route::get('/requisitions/create', [\App\Http\Controllers\Hms\RequisitionController::class, 'create'])->name('requisitions.create');
        Route::post('/requisitions', [\App\Http\Controllers\Hms\RequisitionController::class, 'store'])->name('requisitions.store');
        Route::get('/requisitions/{requisition}', [\App\Http\Controllers\Hms\RequisitionController::class, 'show'])->name('requisitions.show');
        Route::post('/requisitions/{requisition}/approve', [\App\Http\Controllers\Hms\RequisitionController::class, 'approve'])->name('requisitions.approve');
        Route::post('/requisitions/{requisition}/reject', [\App\Http\Controllers\Hms\RequisitionController::class, 'reject'])->name('requisitions.reject');
        Route::post('/requisitions/{requisition}/fulfill', [\App\Http\Controllers\Hms\RequisitionController::class, 'fulfill'])->name('requisitions.fulfill');
        Route::post('/requisitions/{requisition}/cancel', [\App\Http\Controllers\Hms\RequisitionController::class, 'cancel'])->name('requisitions.cancel');

        // Stocktakes
        Route::get('/stocktakes', [\App\Http\Controllers\Hms\StocktakeController::class, 'index'])->name('stocktakes.index');
        Route::get('/stocktakes/create', [\App\Http\Controllers\Hms\StocktakeController::class, 'create'])->name('stocktakes.create');
        Route::post('/stocktakes', [\App\Http\Controllers\Hms\StocktakeController::class, 'store'])->name('stocktakes.store');
        Route::get('/stocktakes/{stocktake}', [\App\Http\Controllers\Hms\StocktakeController::class, 'show'])->name('stocktakes.show');
        Route::post('/stocktakes/{stocktake}/items', [\App\Http\Controllers\Hms\StocktakeController::class, 'updateItems'])->name('stocktakes.items.update');
        Route::post('/stocktakes/{stocktake}/complete', [\App\Http\Controllers\Hms\StocktakeController::class, 'complete'])->name('stocktakes.complete');
        Route::post('/stocktakes/{stocktake}/approve', [\App\Http\Controllers\Hms\StocktakeController::class, 'approve'])->name('stocktakes.approve');
        Route::post('/stocktakes/{stocktake}/adjust', [\App\Http\Controllers\Hms\StocktakeController::class, 'adjust'])->name('stocktakes.adjust');

        // Stock Adjustments
        Route::get('/stock-adjustments', [\App\Http\Controllers\Hms\StockAdjustmentController::class, 'index'])->name('stock-adjustments.index');
        Route::get('/stock-adjustments/create', [\App\Http\Controllers\Hms\StockAdjustmentController::class, 'create'])->name('stock-adjustments.create');
        Route::post('/stock-adjustments', [\App\Http\Controllers\Hms\StockAdjustmentController::class, 'store'])->name('stock-adjustments.store');
        Route::get('/stock-adjustments/{stockAdjustment}', [\App\Http\Controllers\Hms\StockAdjustmentController::class, 'show'])->name('stock-adjustments.show');
        Route::post('/stock-adjustments/{stockAdjustment}/approve', [\App\Http\Controllers\Hms\StockAdjustmentController::class, 'approve'])->name('stock-adjustments.approve');
        Route::post('/stock-adjustments/{stockAdjustment}/reject', [\App\Http\Controllers\Hms\StockAdjustmentController::class, 'reject'])->name('stock-adjustments.reject');

        // Requisitions (Sub-Store ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ Main Store)
        Route::get('/requisitions', [RequisitionController::class, 'index'])->name('requisitions.index');
        Route::get('/requisitions/create', [RequisitionController::class, 'create'])->name('requisitions.create');
        Route::post('/requisitions', [RequisitionController::class, 'store'])->name('requisitions.store');
        Route::get('/requisitions/{requisition}', [RequisitionController::class, 'show'])->name('requisitions.show');
        Route::post('/requisitions/{requisition}/approve', [RequisitionController::class, 'approve'])->name('requisitions.approve');
        Route::post('/requisitions/{requisition}/reject', [RequisitionController::class, 'reject'])->name('requisitions.reject');
        Route::post('/requisitions/{requisition}/fulfill', [RequisitionController::class, 'fulfill'])->name('requisitions.fulfill');
        Route::post('/requisitions/{requisition}/cancel', [RequisitionController::class, 'cancel'])->name('requisitions.cancel');

        // Stocktakes
        Route::get('/stocktakes', [StocktakeController::class, 'index'])->name('stocktakes.index');
        Route::get('/stocktakes/create', [StocktakeController::class, 'create'])->name('stocktakes.create');
        Route::post('/stocktakes', [StocktakeController::class, 'store'])->name('stocktakes.store');
        Route::get('/stocktakes/{stocktake}', [StocktakeController::class, 'show'])->name('stocktakes.show');
        Route::post('/stocktakes/{stocktake}/items', [StocktakeController::class, 'updateItems'])->name('stocktakes.update-items');
        Route::post('/stocktakes/{stocktake}/complete', [StocktakeController::class, 'complete'])->name('stocktakes.complete');
        Route::post('/stocktakes/{stocktake}/approve', [StocktakeController::class, 'approve'])->name('stocktakes.approve');
        Route::post('/stocktakes/{stocktake}/adjust', [StocktakeController::class, 'adjust'])->name('stocktakes.adjust');

        // Stock Adjustments
        Route::get('/stock-adjustments', [StockAdjustmentController::class, 'index'])->name('stock-adjustments.index');
        Route::get('/stock-adjustments/create', [StockAdjustmentController::class, 'create'])->name('stock-adjustments.create');
        Route::post('/stock-adjustments', [StockAdjustmentController::class, 'store'])->name('stock-adjustments.store');
        Route::post('/stock-adjustments/{stockAdjustment}/approve', [StockAdjustmentController::class, 'approve'])->name('stock-adjustments.approve');
        Route::post('/stock-adjustments/{stockAdjustment}/reject', [StockAdjustmentController::class, 'reject'])->name('stock-adjustments.reject');

        Route::get('/hr', [HrController::class, 'index'])->name('hr.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        // Bed Management
        Route::get('/bed-types', [BedTypesController::class, 'index'])->name('bed-types.index');
        Route::get('/bed-types/create', [BedTypesController::class, 'create'])->name('bed-types.create');
        Route::post('/bed-types', [BedTypesController::class, 'store'])->name('bed-types.store');
        Route::get('/bed-types/{bedType}', [BedTypesController::class, 'show'])->name('bed-types.show');
        Route::get('/bed-types/{bedType}/edit', [BedTypesController::class, 'edit'])->name('bed-types.edit');
        Route::put('/bed-types/{bedType}', [BedTypesController::class, 'update'])->name('bed-types.update');
        Route::delete('/bed-types/{bedType}', [BedTypesController::class, 'destroy'])->name('bed-types.destroy');
        Route::get('/beds', [BedsController::class, 'index'])->name('beds.index');
        Route::get('/beds/create', [BedsController::class, 'create'])->name('beds.create');
        Route::post('/beds', [BedsController::class, 'store'])->name('beds.store');
        Route::get('/beds/{bed}', [BedsController::class, 'show'])->name('beds.show');
        Route::get('/beds/{bed}/edit', [BedsController::class, 'edit'])->name('beds.edit');
        Route::put('/beds/{bed}', [BedsController::class, 'update'])->name('beds.update');
        Route::delete('/beds/{bed}', [BedsController::class, 'destroy'])->name('beds.destroy');
        
        // OT Scheduling
        Route::prefix('ot')->name('ot.')->group(function () {
            Route::get('/', [OtSchedulingController::class, 'index'])->name('index');
            Route::get('/create', [OtSchedulingController::class, 'create'])->name('create');
            Route::post('/', [OtSchedulingController::class, 'store'])->name('store');
            Route::get('/schedule', [OtSchedulingController::class, 'schedule'])->name('schedule');
            Route::get('/rooms', [OtSchedulingController::class, 'rooms'])->name('rooms');
            Route::post('/rooms', [OtSchedulingController::class, 'storeRoom'])->name('rooms.store');
            Route::put('/rooms/{room}', [OtSchedulingController::class, 'updateRoom'])->name('rooms.update');
            Route::get('/instruments', [OtSchedulingController::class, 'instruments'])->name('instruments');
            Route::post('/instruments', [OtSchedulingController::class, 'storeInstrument'])->name('instrument-store');
            Route::post('/instruments/{tray}/sterilize', [OtSchedulingController::class, 'sterilize'])->name('instrument-sterilize');
            Route::get('/{schedule}', [OtSchedulingController::class, 'show'])->name('show');
            Route::get('/{schedule}/edit', [OtSchedulingController::class, 'edit'])->name('edit');
            Route::put('/{schedule}', [OtSchedulingController::class, 'update'])->name('update');
            Route::delete('/{schedule}', [OtSchedulingController::class, 'destroy'])->name('destroy');
            Route::post('/{schedule}/time-in', [OtSchedulingController::class, 'timeIn'])->name('time-in');
            Route::post('/{schedule}/time-out', [OtSchedulingController::class, 'timeOut'])->name('time-out');

            // Waiting List
            Route::post('/waiting-list', [\App\Http\Controllers\Hms\WaitingListController::class, 'store'])->name('waiting-list.store');
            Route::post('/waiting-list/{item}/schedule', [\App\Http\Controllers\Hms\WaitingListController::class, 'schedule'])->name('waiting-list.schedule');

            // Pre-op Assessment
            Route::post('/schedules/{schedule}/preop', [\App\Http\Controllers\Hms\PreopAssessmentController::class, 'store'])->name('schedules.preop.store');

            // WHO Safety Checklist
            Route::post('/schedules/{schedule}/checklist/{type}', [\App\Http\Controllers\Hms\WhoChecklistController::class, 'complete'])->name('schedules.checklist.complete');

            // Theatre Team
            Route::post('/schedules/{schedule}/team', [\App\Http\Controllers\Hms\TheatreTeamController::class, 'store'])->name('schedules.team.store');
            Route::post('/team/{member}/remove', [\App\Http\Controllers\Hms\TheatreTeamController::class, 'remove'])->name('team.remove');

            // Theatre Consumables
            Route::post('/schedules/{schedule}/consumables', [\App\Http\Controllers\Hms\TheatreConsumableController::class, 'store'])->name('schedules.consumables.store');

            // Specimens
            Route::post('/schedules/{schedule}/specimens', [\App\Http\Controllers\Hms\SpecimenController::class, 'store'])->name('schedules.specimens.store');

            // Recovery
            Route::post('/recovery', [\App\Http\Controllers\Hms\RecoveryController::class, 'store'])->name('recovery.store');
            Route::post('/recovery/{recovery}/discharge', [\App\Http\Controllers\Hms\RecoveryController::class, 'discharge'])->name('recovery.discharge');
        });

        // Anaesthesia (G022)
        Route::prefix('anaesthesia')->name('anaesthesia.')->middleware('permission:manage anaesthesia assessments|manage anaesthesia records|manage anaesthesia drugs|record intraop vitals|manage anaesthesia complications|record post anaesthesia reviews')->group(function () {
            Route::post('/assessments', [\App\Http\Controllers\Hms\AnaesthesiaAssessmentController::class, 'store'])->name('assessments.store');
            Route::post('/records', [\App\Http\Controllers\Hms\AnaesthesiaRecordController::class, 'store'])->name('records.store');
            Route::post('/records/{record}/complete', [\App\Http\Controllers\Hms\AnaesthesiaRecordController::class, 'complete'])->name('records.complete');
            Route::post('/records/{record}/drugs', [\App\Http\Controllers\Hms\AnaesthesiaDrugController::class, 'store'])->name('records.drugs.store');
            Route::post('/records/{record}/vitals', [\App\Http\Controllers\Hms\IntraopVitalController::class, 'store'])->name('records.vitals.store');
            Route::post('/records/{record}/complications', [\App\Http\Controllers\Hms\AnaesthesiaComplicationController::class, 'store'])->name('records.complications.store');
            Route::post('/records/{record}/post-review', [\App\Http\Controllers\Hms\PostAnaesthesiaReviewController::class, 'store'])->name('records.post-review.store');
        });

        // IPD/OPD ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â requires admission and patient permissions
        Route::middleware('permission:admit patients|manage admissions|view patients')->group(function () {
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
            Route::post('/opd/{opd}/status', [OpdVisitsController::class, 'updateStatus'])->name('opd.update-status');
        Route::post('/opd/{opd}/finalize', [OpdVisitsController::class, 'finalize'])->name('opd.finalize');

            // IPD Inpatient Completion (G017-G018)
            Route::get('/ipd/{ipd}/ward-rounds', [\App\Http\Controllers\Hms\WardRoundController::class, 'index'])->name('ipd.ward-rounds.index');
            Route::post('/ipd/{ipd}/ward-rounds', [\App\Http\Controllers\Hms\WardRoundController::class, 'store'])->name('ipd.ward-rounds.store');
            Route::get('/ipd/{ipd}/nursing-notes', [\App\Http\Controllers\Hms\NursingNoteController::class, 'index'])->name('ipd.nursing-notes.index');
            Route::post('/ipd/{ipd}/nursing-notes', [\App\Http\Controllers\Hms\NursingNoteController::class, 'store'])->name('ipd.nursing-notes.store');
            Route::get('/ipd/{ipd}/fluid-balance', [\App\Http\Controllers\Hms\FluidBalanceController::class, 'index'])->name('ipd.fluid-balance.index');
            Route::post('/ipd/{ipd}/fluid-balance', [\App\Http\Controllers\Hms\FluidBalanceController::class, 'store'])->name('ipd.fluid-balance.store');
            Route::get('/ipd/{ipd}/fluid-balance/summary', [\App\Http\Controllers\Hms\FluidBalanceController::class, 'summary'])->name('ipd.fluid-balance.summary');
            Route::get('/ipd/{ipd}/medications', [\App\Http\Controllers\Hms\MarController::class, 'index'])->name('ipd.mar.index');
            Route::post('/ipd/{ipd}/medications', [\App\Http\Controllers\Hms\MarController::class, 'store'])->name('ipd.mar.store');
            Route::get('/ipd/{ipd}/diet-orders', [\App\Http\Controllers\Hms\DietOrderController::class, 'index'])->name('ipd.diet-orders.index');
            Route::post('/ipd/{ipd}/diet-orders', [\App\Http\Controllers\Hms\DietOrderController::class, 'store'])->name('ipd.diet-orders.store');
        Route::post('/ipd/{ipd}/discharge-summary', [\App\Http\Controllers\Hms\InpatientDischargeSummaryController::class, 'store'])->name('ipd.discharge-summary.store');
        Route::post('/ipd/{ipd}/discharge-summary/sign', [\App\Http\Controllers\Hms\InpatientDischargeSummaryController::class, 'sign'])->name('ipd.discharge-summary.sign');
        Route::get('/ipd/{ipd}/final-bill', [\App\Http\Controllers\Hms\IpdFinalBillController::class, 'show'])->name('ipd.final-bill.show')->middleware('permission:view patients|manage admissions|view invoices|create invoices|view billing');
        Route::post('/ipd/{ipd}/final-bill', [\App\Http\Controllers\Hms\IpdFinalBillController::class, 'generate'])->name('ipd.final-bill.generate')->middleware('permission:view patients|manage admissions|view invoices|create invoices|view billing');
            Route::post('/ipd/{ipd}/transfer', [\App\Http\Controllers\Hms\TransferController::class, 'store'])->name('ipd.transfer.store');

            // ICU & HDU Module (G019)
            Route::get('/icu/admissions', [\App\Http\Controllers\Hms\IcuAdmissionController::class, 'index'])->name('icu.index');
            Route::post('/icu/admissions', [\App\Http\Controllers\Hms\IcuAdmissionController::class, 'store'])->name('icu.store');
            Route::post('/icu/admissions/{admission}/discharge', [\App\Http\Controllers\Hms\IcuAdmissionController::class, 'discharge'])->name('icu.discharge');
            Route::post('/icu/admissions/{admission}/charts', [\App\Http\Controllers\Hms\CriticalCareChartController::class, 'store'])->name('icu.charts.store');
            Route::post('/icu/admissions/{admission}/ventilators', [\App\Http\Controllers\Hms\VentilatorController::class, 'store'])->name('icu.ventilators.store');
            Route::post('/icu/ventilators/{ventilator}/stop', [\App\Http\Controllers\Hms\VentilatorController::class, 'stop'])->name('icu.ventilators.stop');
            Route::post('/icu/admissions/{admission}/abg', [\App\Http\Controllers\Hms\AbgController::class, 'store'])->name('icu.abg.store');
            Route::post('/icu/admissions/{admission}/sedation', [\App\Http\Controllers\Hms\SedationController::class, 'store'])->name('icu.sedation.store');
            Route::post('/icu/admissions/{admission}/infusions', [\App\Http\Controllers\Hms\InfusionController::class, 'store'])->name('icu.infusions.store');
            Route::post('/icu/infusions/{infusion}/stop', [\App\Http\Controllers\Hms\InfusionController::class, 'stop'])->name('icu.infusions.stop');

            // OPD Consultation Completion (G008-G010)
            Route::get('/consultations/{opd}/clinical-notes', [\App\Http\Controllers\Hms\ClinicalNoteController::class, 'index'])->name('consultations.clinical-notes.index');
            Route::post('/consultations/{opd}/clinical-notes', [\App\Http\Controllers\Hms\ClinicalNoteController::class, 'store'])->name('consultations.clinical-notes.store');
            Route::post('/consultations/{opd}/procedure-orders', [\App\Http\Controllers\Hms\ProcedureOrderController::class, 'store'])->name('consultations.procedure-orders.store');
            Route::post('/consultations/procedure-orders/{order}/complete', [\App\Http\Controllers\Hms\ProcedureOrderController::class, 'complete'])->name('consultations.procedure-orders.complete');
            Route::post('/sick-notes', [\App\Http\Controllers\Hms\SickNoteController::class, 'store'])->name('sick-notes.store');
            Route::post('/medical-certificates', [\App\Http\Controllers\Hms\MedicalCertificateController::class, 'store'])->name('medical-certificates.store');
        });

        // Triage ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â requires vitals permission
        Route::middleware('permission:manage patient vitals|view patients')->group(function () {
            // Triage Escalations
            Route::get('/triage/escalations', [\App\Http\Controllers\Hms\TriageEscalationController::class, 'index'])->name('triage.escalations.index');
            Route::post('/triage/escalations/{escalation}/acknowledge', [\App\Http\Controllers\Hms\TriageEscalationController::class, 'acknowledge'])->name('triage.escalations.acknowledge');
            Route::post('/triage/escalations/{escalation}/resolve', [\App\Http\Controllers\Hms\TriageEscalationController::class, 'resolve'])->name('triage.escalations.resolve');

            Route::get('/triage', [\App\Http\Controllers\Hms\TriageController::class, 'index'])->name('triage.index');
            Route::get('/triage/create', [\App\Http\Controllers\Hms\TriageController::class, 'create'])->name('triage.create');
            Route::post('/triage', [\App\Http\Controllers\Hms\TriageController::class, 'store'])->name('triage.store');
            Route::get('/triage/{triage}/edit', [\App\Http\Controllers\Hms\TriageController::class, 'edit'])->name('triage.edit');
            Route::put('/triage/{triage}', [\App\Http\Controllers\Hms\TriageController::class, 'update'])->name('triage.update');
            Route::delete('/triage/{triage}', [\App\Http\Controllers\Hms\TriageController::class, 'destroy'])->name('triage.destroy');
            Route::post('/triage/{triage}/escalate', [\App\Http\Controllers\Hms\TriageEscalationController::class, 'store'])->name('triage.escalations.store');
            Route::get('/triage/{triage}', [\App\Http\Controllers\Hms\TriageController::class, 'show'])->name('triage.show');
        });

        // Vitals
        Route::get('/vitals', [\App\Http\Controllers\Hms\VitalsController::class, 'index'])->name('vitals.index');
        Route::get('/vitals/create', [\App\Http\Controllers\Hms\VitalsController::class, 'create'])->name('vitals.create');
        Route::post('/vitals', [\App\Http\Controllers\Hms\VitalsController::class, 'store'])->name('vitals.store');
        Route::get('/vitals/{vital}', [\App\Http\Controllers\Hms\VitalsController::class, 'show'])->name('vitals.show');
        Route::get('/vitals/{vital}/edit', [\App\Http\Controllers\Hms\VitalsController::class, 'edit'])->name('vitals.edit');
        Route::put('/vitals/{vital}', [\App\Http\Controllers\Hms\VitalsController::class, 'update'])->name('vitals.update');
        Route::delete('/vitals/{vital}', [\App\Http\Controllers\Hms\VitalsController::class, 'destroy'])->name('vitals.destroy');
        Route::get('/vitals/patient/{patient}', [\App\Http\Controllers\Hms\VitalsController::class, 'patientHistory'])->name('vitals.patient-history');

        // Wards
        Route::get('/wards', [\App\Http\Controllers\Hms\WardController::class, 'index'])->name('wards.index');
        Route::get('/wards/create', [\App\Http\Controllers\Hms\WardController::class, 'create'])->name('wards.create');
        Route::post('/wards', [\App\Http\Controllers\Hms\WardController::class, 'store'])->name('wards.store');
        Route::get('/wards/{ward}', [\App\Http\Controllers\Hms\WardController::class, 'show'])->name('wards.show');
        Route::get('/wards/{ward}/edit', [\App\Http\Controllers\Hms\WardController::class, 'edit'])->name('wards.edit');
        Route::put('/wards/{ward}', [\App\Http\Controllers\Hms\WardController::class, 'update'])->name('wards.update');
        Route::delete('/wards/{ward}', [\App\Http\Controllers\Hms\WardController::class, 'destroy'])->name('wards.destroy');

        // Referrals
        Route::get('/referrals', [\App\Http\Controllers\Hms\ReferralController::class, 'index'])->name('referrals.index');
        Route::get('/referrals/create', [\App\Http\Controllers\Hms\ReferralController::class, 'create'])->name('referrals.create');
        Route::post('/referrals', [\App\Http\Controllers\Hms\ReferralController::class, 'store'])->name('referrals.store');

        // Referring Facilities (G075-G078) ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â must be before {referral} routes
        Route::get('/referrals/facilities', [\App\Http\Controllers\Hms\ReferringFacilityController::class, 'index'])->name('referrals.facilities.index');
        Route::post('/referrals/facilities', [\App\Http\Controllers\Hms\ReferringFacilityController::class, 'store'])->name('referrals.facilities.store');

        Route::get('/referrals/{referral}', [\App\Http\Controllers\Hms\ReferralController::class, 'show'])->name('referrals.show');
        Route::get('/referrals/{referral}/edit', [\App\Http\Controllers\Hms\ReferralController::class, 'edit'])->name('referrals.edit');
        Route::put('/referrals/{referral}', [\App\Http\Controllers\Hms\ReferralController::class, 'update'])->name('referrals.update');
        Route::delete('/referrals/{referral}', [\App\Http\Controllers\Hms\ReferralController::class, 'destroy'])->name('referrals.destroy');
        Route::post('/referrals/{referral}/accept', [\App\Http\Controllers\Hms\ReferralController::class, 'accept'])->name('referrals.accept');
        Route::post('/referrals/{referral}/complete', [\App\Http\Controllers\Hms\ReferralController::class, 'complete'])->name('referrals.complete');
        Route::post('/referrals/{referral}/reject', [\App\Http\Controllers\Hms\ReferralController::class, 'reject'])->name('referrals.reject');

        // Schedule Slots (G075-G078)
        Route::get('/schedules/{schedule}/slots', [\App\Http\Controllers\Hms\ScheduleSlotController::class, 'index'])->name('schedules.slots.index');
        Route::post('/schedules/{schedule}/slots', [\App\Http\Controllers\Hms\ScheduleSlotController::class, 'store'])->name('schedules.slots.store');

        // Appointment Reminders (G075-G078)
        Route::post('/appointments/{appointment}/reminders', [\App\Http\Controllers\Hms\AppointmentReminderController::class, 'store'])->name('appointments.reminders.store');
        Route::post('/appointments/reminders/{reminder}/send', [\App\Http\Controllers\Hms\AppointmentReminderController::class, 'send'])->name('appointments.reminders.send');

        // Referral Documents (G075-G078)
        Route::post('/referrals/{referral}/documents', [\App\Http\Controllers\Hms\ReferralDocumentController::class, 'store'])->name('referrals.documents.store');

        // Referral Feedback (G075-G078)
        Route::post('/referrals/{referral}/feedback', [\App\Http\Controllers\Hms\ReferralFeedbackController::class, 'store'])->name('referrals.feedback.store');

        // Nursing Care Plans
        Route::get('/nursing-care-plans', [\App\Http\Controllers\Hms\NursingCarePlanController::class, 'index'])->name('nursing-care-plans.index');
        Route::get('/nursing-care-plans/create', [\App\Http\Controllers\Hms\NursingCarePlanController::class, 'create'])->name('nursing-care-plans.create');
        Route::post('/nursing-care-plans', [\App\Http\Controllers\Hms\NursingCarePlanController::class, 'store'])->name('nursing-care-plans.store');
        Route::get('/nursing-care-plans/{carePlan}', [\App\Http\Controllers\Hms\NursingCarePlanController::class, 'show'])->name('nursing-care-plans.show');
        Route::get('/nursing-care-plans/{carePlan}/edit', [\App\Http\Controllers\Hms\NursingCarePlanController::class, 'edit'])->name('nursing-care-plans.edit');
        Route::put('/nursing-care-plans/{carePlan}', [\App\Http\Controllers\Hms\NursingCarePlanController::class, 'update'])->name('nursing-care-plans.update');
        Route::delete('/nursing-care-plans/{carePlan}', [\App\Http\Controllers\Hms\NursingCarePlanController::class, 'destroy'])->name('nursing-care-plans.destroy');
        
        // Billing
        Route::get('/billing/invoices', [InvoicesController::class, 'index'])->name('billing.invoices.index')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::get('/billing/invoices/create', [InvoicesController::class, 'create'])->name('billing.invoices.create')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/invoices', [InvoicesController::class, 'store'])->name('billing.invoices.store')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::get('/billing/invoices/{invoice}', [InvoicesController::class, 'show'])->name('billing.invoices.show')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::get('/billing/invoices/{invoice}/pdf', [InvoicesController::class, 'generatePdf'])->name('billing.invoices.pdf')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/invoices/{invoice}/email', [InvoicesController::class, 'sendEmail'])->name('billing.invoices.email')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::get('/billing/invoices/{invoice}/edit', [InvoicesController::class, 'edit'])->name('billing.invoices.edit')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::put('/billing/invoices/{invoice}', [InvoicesController::class, 'update'])->name('billing.invoices.update')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::delete('/billing/invoices/{invoice}', [InvoicesController::class, 'destroy'])->name('billing.invoices.destroy')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        
        Route::get('/billing/payments', [PaymentsController::class, 'index'])->name('billing.payments.index')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::get('/billing/payments/create', [PaymentsController::class, 'create'])->name('billing.payments.create')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/payments', [PaymentsController::class, 'store'])->name('billing.payments.store')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::get('/billing/payments/{payment}', [PaymentsController::class, 'show'])->name('billing.payments.show')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::get('/billing/payments/{payment}/edit', [PaymentsController::class, 'edit'])->name('billing.payments.edit')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::put('/billing/payments/{payment}', [PaymentsController::class, 'update'])->name('billing.payments.update')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::delete('/billing/payments/{payment}', [PaymentsController::class, 'destroy'])->name('billing.payments.destroy')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::get('/billing/payments/{payment}/thermal-receipt', [PaymentsController::class, 'thermalReceipt'])->name('billing.payments.thermal-receipt')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::get('/billing/invoices/{invoice}/thermal-receipt', [PaymentsController::class, 'invoiceThermalReceipt'])->name('billing.invoices.thermal-receipt')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');

        // Charges, Refunds, Discounts, Waivers, Cashier Sessions
        Route::post('/billing/charges', [\App\Http\Controllers\Hms\ChargesController::class, 'store'])->name('billing.charges.store')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/charges/{charge}/reverse', [\App\Http\Controllers\Hms\ChargesController::class, 'reverse'])->name('billing.charges.reverse')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/refunds', [\App\Http\Controllers\Hms\RefundController::class, 'store'])->name('billing.refunds.store')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/refunds/{refund}/approve', [\App\Http\Controllers\Hms\RefundController::class, 'approve'])->name('billing.refunds.approve')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/refunds/{refund}/reject', [\App\Http\Controllers\Hms\RefundController::class, 'reject'])->name('billing.refunds.reject')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/payments/{payment}/refund', [PaymentsController::class, 'requestRefund'])->name('billing.payments.request-refund')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/discounts', [\App\Http\Controllers\Hms\DiscountWaiverController::class, 'storeDiscount'])->name('billing.discounts.store')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/waivers', [\App\Http\Controllers\Hms\DiscountWaiverController::class, 'storeWaiver'])->name('billing.waivers.store')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/waivers/{waiver}/approve', [\App\Http\Controllers\Hms\DiscountWaiverController::class, 'approveWaiver'])->name('billing.waivers.approve')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/waivers/{waiver}/reject', [\App\Http\Controllers\Hms\DiscountWaiverController::class, 'rejectWaiver'])->name('billing.waivers.reject')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/cashier-sessions', [\App\Http\Controllers\Hms\CashierSessionController::class, 'store'])->name('billing.cashier-sessions.store')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::post('/billing/cashier-sessions/close', [\App\Http\Controllers\Hms\CashierSessionController::class, 'close'])->name('billing.cashier-sessions.close')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');

        // M-Pesa Payments (STK push initiation, status polling, SHA coverage check)
        Route::post('/mpesa/initiate', [\App\Http\Controllers\Hms\MpesaPaymentsController::class, 'initiate'])->name('mpesa.initiate');
        Route::get('/mpesa/status/{payment}', [\App\Http\Controllers\Hms\MpesaPaymentsController::class, 'status'])->name('mpesa.status');
        Route::post('/mpesa/coverage', [\App\Http\Controllers\Hms\MpesaPaymentsController::class, 'coverage'])->name('mpesa.coverage');
        Route::get('/mpesa/receipt/{payment}', [\App\Http\Controllers\Hms\MpesaPaymentsController::class, 'receipt'])->name('mpesa.receipt');
        
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
        Route::get('/insurance/claims/{claim}/pdf', [\App\Http\Controllers\Hms\InsuranceClaimsController::class, 'generatePdf'])->name('insurance.claims.pdf')->middleware('permission:manage insurance claims|view payment reports|export reports');

        // Claim Batches
        Route::post('/insurance/claim-batches', [\App\Http\Controllers\Hms\ClaimBatchController::class, 'store'])->name('insurance.claim-batches.store');
        Route::post('/insurance/claim-batches/{batch}/submit', [\App\Http\Controllers\Hms\ClaimBatchController::class, 'submit'])->name('insurance.claim-batches.submit');

        // Claim Rejections
        Route::post('/insurance/claims/{claim}/rejection', [\App\Http\Controllers\Hms\ClaimRejectionController::class, 'store'])->name('insurance.claims.rejection.store');
        Route::post('/insurance/rejections/{rejection}/handle', [\App\Http\Controllers\Hms\ClaimRejectionController::class, 'handle'])->name('insurance.rejections.handle');

        // Claim Remittances
        Route::post('/insurance/claim-batches/{batch}/remittance', [\App\Http\Controllers\Hms\ClaimRemittanceController::class, 'store'])->name('insurance.claim-batches.remittance.store');
        Route::post('/insurance/remittances/{remittance}/reconcile', [\App\Http\Controllers\Hms\ClaimRemittanceController::class, 'reconcile'])->name('insurance.remittances.reconcile');

        // Tariffs
        Route::get('/insurance/tariffs', [\App\Http\Controllers\Hms\TariffController::class, 'index'])->name('insurance.tariffs.index');
        Route::post('/insurance/tariffs', [\App\Http\Controllers\Hms\TariffController::class, 'store'])->name('insurance.tariffs.store');
        Route::put('/insurance/tariffs/{tariff}', [\App\Http\Controllers\Hms\TariffController::class, 'update'])->name('insurance.tariffs.update');
        Route::delete('/insurance/tariffs/{tariff}', [\App\Http\Controllers\Hms\TariffController::class, 'destroy'])->name('insurance.tariffs.destroy');
        
        // Pharmacy
        // Pharmacy medicines & prescriptions ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â permission-gated
        Route::middleware('permission:view prescriptions|create prescriptions|edit prescriptions|dispense medicines|manage medicine inventory|verify dispensations|manage controlled drugs|process drug returns')->group(function () {
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
            Route::post('/pharmacy/prescriptions/{prescription}/dispense', [\App\Http\Controllers\Hms\PharmacyController::class, 'dispensePrescription'])->name('pharmacy.prescriptions.dispense');

            // Dispensations (G034-G036)
            Route::post('/pharmacy/dispensations', [\App\Http\Controllers\Hms\DispensationController::class, 'store'])->name('pharmacy.dispensations.store');
            Route::post('/pharmacy/dispensations/{dispensation}/verify', [\App\Http\Controllers\Hms\DispensationController::class, 'verify'])->name('pharmacy.dispensations.verify');

            // Drug Returns
            Route::post('/pharmacy/returns', [\App\Http\Controllers\Hms\DrugReturnController::class, 'store'])->name('pharmacy.returns.store');
            Route::post('/pharmacy/returns/{return}/approve', [\App\Http\Controllers\Hms\DrugReturnController::class, 'approve'])->name('pharmacy.returns.approve');
            Route::post('/pharmacy/returns/{return}/process', [\App\Http\Controllers\Hms\DrugReturnController::class, 'process'])->name('pharmacy.returns.process');
        });

        // Controlled Drug Register
        Route::get('/pharmacy/controlled-drugs', [\App\Http\Controllers\Hms\ControlledDrugController::class, 'index'])->name('pharmacy.controlled-drugs.index');
        Route::post('/pharmacy/controlled-drugs', [\App\Http\Controllers\Hms\ControlledDrugController::class, 'recordTransaction'])->name('pharmacy.controlled-drugs.store');

        // GRN (Goods Received Notes)
        Route::post('/pharmacy/grn', [\App\Http\Controllers\Hms\GrnController::class, 'store'])->name('pharmacy.grn.store');
        Route::post('/pharmacy/grn/{grn}/verify', [\App\Http\Controllers\Hms\GrnController::class, 'verify'])->name('pharmacy.grn.verify');

        // E-Prescription Routes
        Route::prefix('prescriptions/e-prescription')->name('prescriptions.e-prescription.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'index'])->name('index');
            Route::get('/templates', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'templates'])->name('templates');
            Route::get('/create', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'store'])->name('store');
            Route::get('/{prescription}', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'show'])->name('show');
            Route::get('/{prescription}/edit', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'edit'])->name('edit');
            Route::put('/{prescription}', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'update'])->name('update');
            Route::delete('/{prescription}', [\App\Http\Controllers\Hms\EPrescriptionController::class, 'destroy'])->name('destroy');
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
        
        Route::get('/laboratory/requests', [LabRequestsController::class, 'index'])->name('laboratory.requests.index')->middleware('permission:add test requests|view test results|manage lab specimens|manage lab worklists|verify lab results|print lab reports|download lab reports|enter test results|approve test results');
        Route::get('/laboratory/requests/{labRequest}/results', [\App\Http\Controllers\Hms\LaboratoryController::class, 'resultsForm'])->name('laboratory.requests.results')->middleware('permission:enter test results|verify lab results|approve test results');
        Route::post('/laboratory/requests/{labRequest}/results', [\App\Http\Controllers\Hms\LaboratoryController::class, 'processRequest'])->name('laboratory.requests.results.store')->middleware('permission:enter test results|verify lab results|approve test results');
        Route::get('/laboratory/requests/create', [LabRequestsController::class, 'create'])->name('laboratory.requests.create')->middleware('permission:add test requests|view test results|manage lab specimens|manage lab worklists|verify lab results|print lab reports|download lab reports|enter test results|approve test results');
        Route::post('/laboratory/requests', [LabRequestsController::class, 'store'])->name('laboratory.requests.store')->middleware('permission:add test requests|view test results|manage lab specimens|manage lab worklists|verify lab results|print lab reports|download lab reports|enter test results|approve test results');
        Route::get('/laboratory/requests/{labRequest}', [LabRequestsController::class, 'show'])->name('laboratory.requests.show')->middleware('permission:add test requests|view test results|manage lab specimens|manage lab worklists|verify lab results|print lab reports|download lab reports|enter test results|approve test results');
        Route::get('/laboratory/requests/{labRequest}/edit', [LabRequestsController::class, 'edit'])->name('laboratory.requests.edit')->middleware('permission:add test requests|view test results|manage lab specimens|manage lab worklists|verify lab results|print lab reports|download lab reports|enter test results|approve test results');
        Route::put('/laboratory/requests/{labRequest}', [LabRequestsController::class, 'update'])->name('laboratory.requests.update')->middleware('permission:add test requests|view test results|manage lab specimens|manage lab worklists|verify lab results|print lab reports|download lab reports|enter test results|approve test results');
        Route::delete('/laboratory/requests/{labRequest}', [LabRequestsController::class, 'destroy'])->name('laboratory.requests.destroy')->middleware('permission:add test requests|view test results|manage lab specimens|manage lab worklists|verify lab results|print lab reports|download lab reports|enter test results|approve test results');
        Route::get('/laboratory/requests/{labRequest}/report-pdf', [LabRequestsController::class, 'reportPdf'])->name('laboratory.requests.report-pdf')->middleware('permission:add test requests|view test results|manage lab specimens|manage lab worklists|verify lab results|print lab reports|download lab reports|enter test results|approve test results');
        
        Route::get('/laboratory/technicians', [LaboratoryController::class, 'technicians'])->name('laboratory.technicians.index');
        Route::get('/laboratory/technicians/create', [LaboratoryController::class, 'createTechnician'])->name('laboratory.technicians.create');
        Route::post('/laboratory/technicians', [LaboratoryController::class, 'storeTechnician'])->name('laboratory.technicians.store');
        Route::get('/laboratory/reports', [LaboratoryController::class, 'reports'])->name('laboratory.reports');

        // Lab Specimens
        Route::post('/lab/specimens', [\App\Http\Controllers\Hms\LabSpecimenController::class, 'store'])->name('lab.specimens.store');
        Route::post('/lab/specimens/{specimen}/receive', [\App\Http\Controllers\Hms\LabSpecimenController::class, 'receive'])->name('lab.specimens.receive');
        Route::post('/lab/specimens/{specimen}/reject', [\App\Http\Controllers\Hms\LabSpecimenController::class, 'reject'])->name('lab.specimens.reject');

        // Lab Worklists
        Route::post('/lab/worklists', [\App\Http\Controllers\Hms\LabWorklistController::class, 'store'])->name('lab.worklists.store');
        Route::post('/lab/worklists/{worklist}/items', [\App\Http\Controllers\Hms\LabWorklistController::class, 'addItem'])->name('lab.worklists.items');
        Route::post('/lab/worklists/{worklist}/complete', [\App\Http\Controllers\Hms\LabWorklistController::class, 'complete'])->name('lab.worklists.complete');

        // Lab Result Verifications
        Route::post('/lab/verifications/{item}/verify', [\App\Http\Controllers\Hms\LabVerificationController::class, 'verify'])->name('lab.verifications.verify');
        Route::post('/lab/verifications/{item}/approve', [\App\Http\Controllers\Hms\LabVerificationController::class, 'approve'])->name('lab.verifications.approve');
        Route::post('/lab/verifications/{item}/reject', [\App\Http\Controllers\Hms\LabVerificationController::class, 'reject'])->name('lab.verifications.reject');
        
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
        Route::get('/radiology/requests/{radiologyRequest}/edit', [RadiologyRequestsController::class, 'edit'])->name('radiology.requests.edit');
        Route::put('/radiology/requests/{radiologyRequest}', [RadiologyRequestsController::class, 'update'])->name('radiology.requests.update');
        Route::delete('/radiology/requests/{radiologyRequest}', [RadiologyRequestsController::class, 'destroy'])->name('radiology.requests.destroy');
        Route::get('/radiology/requests/{radiologyRequest}/report-pdf', [RadiologyRequestsController::class, 'reportPdf'])->name('radiology.requests.report-pdf');

        // Radiology - Scheduling, Worklist, Reports, Contrast (G028-G030)
        Route::post('/radiology/schedules', [\App\Http\Controllers\Hms\ImagingScheduleController::class, 'store'])->name('radiology.schedules.store');
        Route::post('/radiology/schedules/{schedule}/complete', [\App\Http\Controllers\Hms\ImagingScheduleController::class, 'complete'])->name('radiology.schedules.complete');
        Route::post('/radiology/schedules/{schedule}/cancel', [\App\Http\Controllers\Hms\ImagingScheduleController::class, 'cancel'])->name('radiology.schedules.cancel');
        Route::get('/radiology/worklist', [\App\Http\Controllers\Hms\ModalityWorklistController::class, 'index'])->name('radiology.worklist.index');
        Route::post('/radiology/worklist/{item}/claim', [\App\Http\Controllers\Hms\ModalityWorklistController::class, 'claim'])->name('radiology.worklist.claim');
        Route::post('/radiology/worklist/{item}/complete', [\App\Http\Controllers\Hms\ModalityWorklistController::class, 'complete'])->name('radiology.worklist.complete');
        Route::post('/radiology/{radiologyRequest}/report', [\App\Http\Controllers\Hms\ImagingReportController::class, 'store'])->name('radiology.report.store');
        Route::post('/radiology/{radiologyRequest}/report/approve', [\App\Http\Controllers\Hms\ImagingReportController::class, 'approve'])->name('radiology.report.approve');
        Route::post('/radiology/{radiologyRequest}/report/amend', [\App\Http\Controllers\Hms\ImagingReportController::class, 'amend'])->name('radiology.report.amend');
        Route::post('/radiology/{radiologyRequest}/contrast', [\App\Http\Controllers\Hms\ContrastController::class, 'store'])->name('radiology.contrast.store');

        // HR Management
        Route::get('/hr/employees', [EmployeesController::class, 'index'])->name('hr.employees.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/employees/create', [EmployeesController::class, 'create'])->name('hr.employees.create')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/employees', [EmployeesController::class, 'store'])->name('hr.employees.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/employees/{employee}', [HrController::class, 'showEmployee'])->name('hr.employees.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/employees/{employee}/edit', [HrController::class, 'editEmployee'])->name('hr.employees.edit')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::put('/hr/employees/{employee}', [HrController::class, 'updateEmployee'])->name('hr.employees.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::delete('/hr/employees/{employee}', [HrController::class, 'destroyEmployee'])->name('hr.employees.destroy')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        // HR Departments
        Route::get('/hr/departments', [EmployeeDepartmentsController::class, 'index'])->name('hr.departments.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/departments/create', [EmployeeDepartmentsController::class, 'create'])->name('hr.departments.create')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/departments', [EmployeeDepartmentsController::class, 'store'])->name('hr.departments.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/departments/{department}', [EmployeeDepartmentsController::class, 'show'])->name('hr.departments.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/departments/{department}/edit', [EmployeeDepartmentsController::class, 'edit'])->name('hr.departments.edit')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::put('/hr/departments/{department}', [EmployeeDepartmentsController::class, 'update'])->name('hr.departments.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::delete('/hr/departments/{department}', [EmployeeDepartmentsController::class, 'destroy'])->name('hr.departments.destroy')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/employee-departments/create', [EmployeeDepartmentsController::class, 'create'])->name('employee-departments.create');
        Route::get('/employee-departments/{department}', [EmployeeDepartmentsController::class, 'show'])->name('employee-departments.show');
        Route::get('/hr/payrolls', [PayrollsController::class, 'index'])->name('hr.payrolls.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/payrolls/create', [PayrollsController::class, 'create'])->name('hr.payrolls.create')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/payrolls', [PayrollsController::class, 'store'])->name('hr.payrolls.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/payrolls/{payroll}', [PayrollsController::class, 'show'])->name('hr.payrolls.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/payrolls/{payroll}/edit', [PayrollsController::class, 'edit'])->name('hr.payrolls.edit')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::put('/hr/payrolls/{payroll}', [PayrollsController::class, 'update'])->name('hr.payrolls.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::delete('/hr/payrolls/{payroll}', [PayrollsController::class, 'destroy'])->name('hr.payrolls.destroy')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/schedules', [SchedulesController::class, 'index'])->name('hr.schedules.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/schedules/create', [SchedulesController::class, 'create'])->name('hr.schedules.create')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/schedules', [SchedulesController::class, 'store'])->name('hr.schedules.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/schedules/{schedule}', [SchedulesController::class, 'show'])->name('hr.schedules.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/schedules/{schedule}/edit', [SchedulesController::class, 'edit'])->name('hr.schedules.edit')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::put('/hr/schedules/{schedule}', [SchedulesController::class, 'update'])->name('hr.schedules.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::delete('/hr/schedules/{schedule}', [SchedulesController::class, 'destroy'])->name('hr.schedules.destroy')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/attendance', [AttendanceController::class, 'index'])->name('hr.attendance.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/attendance/create', [AttendanceController::class, 'create'])->name('hr.attendance.create')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/attendance', [AttendanceController::class, 'store'])->name('hr.attendance.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/attendance/{attendance}', [AttendanceController::class, 'show'])->name('hr.attendance.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('hr.attendance.edit')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::put('/hr/attendance/{attendance}', [AttendanceController::class, 'update'])->name('hr.attendance.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::delete('/hr/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('hr.attendance.destroy')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/leave-requests', [LeaveRequestsController::class, 'index'])->name('hr.leave-requests.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/leave-requests/create', [LeaveRequestsController::class, 'create'])->name('hr.leave-requests.create')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/leave-requests', [LeaveRequestsController::class, 'store'])->name('hr.leave-requests.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/leave-requests/{leaveRequest}', [LeaveRequestsController::class, 'show'])->name('hr.leave-requests.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/leave-requests/{leaveRequest}/edit', [LeaveRequestsController::class, 'edit'])->name('hr.leave-requests.edit')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::put('/hr/leave-requests/{leaveRequest}', [LeaveRequestsController::class, 'update'])->name('hr.leave-requests.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::delete('/hr/leave-requests/{leaveRequest}', [LeaveRequestsController::class, 'destroy'])->name('hr.leave-requests.destroy')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/leave-requests/{leaveRequest}/approve', [LeaveRequestsController::class, 'approve'])->name('hr.leave-requests.approve')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/leave-requests/{leaveRequest}/reject', [LeaveRequestsController::class, 'reject'])->name('hr.leave-requests.reject')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');

        // Leave Balances
        Route::get('/hr/leave-balances', [\App\Http\Controllers\Hms\LeaveBalanceController::class, 'index'])->name('hr.leave-balances.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/leave-balances/employee/{employee}', [\App\Http\Controllers\Hms\LeaveBalanceController::class, 'employeeBalance'])->name('hr.leave-balances.employee')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/leave-balances', [\App\Http\Controllers\Hms\LeaveBalanceController::class, 'store'])->name('hr.leave-balances.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/leave-balances/seed', [\App\Http\Controllers\Hms\LeaveBalanceController::class, 'seedBalances'])->name('hr.leave-balances.seed')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');

        // G070-G072: HR Completion ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â Contracts, Disciplinary, Licences, Payroll Export, Staff Documents
        Route::get('/hr/contracts', [\App\Http\Controllers\Hms\ContractController::class, 'index'])->name('hr.contracts.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/contracts', [\App\Http\Controllers\Hms\ContractController::class, 'store'])->name('hr.contracts.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/disciplinary', [\App\Http\Controllers\Hms\DisciplinaryRecordController::class, 'index'])->name('hr.disciplinary.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/disciplinary', [\App\Http\Controllers\Hms\DisciplinaryRecordController::class, 'store'])->name('hr.disciplinary.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/disciplinary/{record}/resolve', [\App\Http\Controllers\Hms\DisciplinaryRecordController::class, 'resolve'])->name('hr.disciplinary.resolve')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/licences', [\App\Http\Controllers\Hms\StaffLicenceController::class, 'index'])->name('hr.licences.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/licences', [\App\Http\Controllers\Hms\StaffLicenceController::class, 'store'])->name('hr.licences.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/licences/expiring', [\App\Http\Controllers\Hms\StaffLicenceController::class, 'expiring'])->name('hr.licences.expiring')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/payroll-export', [\App\Http\Controllers\Hms\PayrollExportController::class, 'index'])->name('hr.payroll-export.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/payroll-export', [\App\Http\Controllers\Hms\PayrollExportController::class, 'store'])->name('hr.payroll-export.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/staff-documents', [\App\Http\Controllers\Hms\StaffDocumentController::class, 'index'])->name('hr.staff-documents.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/staff-documents', [\App\Http\Controllers\Hms\StaffDocumentController::class, 'store'])->name('hr.staff-documents.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');

        // Student Rotations
        Route::get('/hr/student-rotations', [\App\Http\Controllers\Hms\StudentRotationController::class, 'index'])->name('hr.student-rotations.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/student-rotations/create', [\App\Http\Controllers\Hms\StudentRotationController::class, 'create'])->name('hr.student-rotations.create')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/student-rotations', [\App\Http\Controllers\Hms\StudentRotationController::class, 'store'])->name('hr.student-rotations.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/student-rotations/{studentRotation}', [\App\Http\Controllers\Hms\StudentRotationController::class, 'show'])->name('hr.student-rotations.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/student-rotations/{studentRotation}/edit', [\App\Http\Controllers\Hms\StudentRotationController::class, 'edit'])->name('hr.student-rotations.edit')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::put('/hr/student-rotations/{studentRotation}', [\App\Http\Controllers\Hms\StudentRotationController::class, 'update'])->name('hr.student-rotations.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/student-rotations/{studentRotation}/evaluate', [\App\Http\Controllers\Hms\StudentRotationController::class, 'evaluate'])->name('hr.student-rotations.evaluate')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::delete('/hr/student-rotations/{studentRotation}', [\App\Http\Controllers\Hms\StudentRotationController::class, 'destroy'])->name('hr.student-rotations.destroy')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');

        // Internships
        Route::get('/hr/internships', [\App\Http\Controllers\Hms\InternshipController::class, 'index'])->name('hr.internships.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/internships/create', [\App\Http\Controllers\Hms\InternshipController::class, 'create'])->name('hr.internships.create')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/internships', [\App\Http\Controllers\Hms\InternshipController::class, 'store'])->name('hr.internships.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/internships/{internship}', [\App\Http\Controllers\Hms\InternshipController::class, 'show'])->name('hr.internships.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/internships/{internship}/edit', [\App\Http\Controllers\Hms\InternshipController::class, 'edit'])->name('hr.internships.edit')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::put('/hr/internships/{internship}', [\App\Http\Controllers\Hms\InternshipController::class, 'update'])->name('hr.internships.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/internships/{internship}/complete', [\App\Http\Controllers\Hms\InternshipController::class, 'complete'])->name('hr.internships.complete')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::delete('/hr/internships/{internship}', [\App\Http\Controllers\Hms\InternshipController::class, 'destroy'])->name('hr.internships.destroy')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        // Blood Bank
        Route::get('/bloodbank', [BloodBankController::class, 'index'])->name('bloodbank.index');
        Route::get('/bloodbank/donors', [BloodBankController::class, 'donors'])->name('bloodbank.donors');
        Route::get('/bloodbank/requests', [BloodBankController::class, 'requests'])->name('bloodbank.requests');
        Route::get('/bloodbank/donors/create', [BloodBankController::class, 'createDonor'])->name('bloodbank.donors.create');
        Route::post('/bloodbank/donors', [BloodBankController::class, 'storeDonor'])->name('bloodbank.donors.store');
        Route::get('/bloodbank/donors/{donor}', [BloodBankController::class, 'showDonor'])->name('bloodbank.donors.show');
        Route::get('/bloodbank/donors/{donor}/edit', [BloodBankController::class, 'editDonor'])->name('bloodbank.donors.edit');
        Route::put('/bloodbank/donors/{donor}', [BloodBankController::class, 'updateDonor'])->name('bloodbank.donors.update');
        Route::delete('/bloodbank/donors/{donor}', [BloodBankController::class, 'destroyDonor'])->name('bloodbank.donors.destroy');
        Route::get('/bloodbank/requests/create', [BloodBankController::class, 'createRequest'])->name('bloodbank.requests.create');
        Route::post('/bloodbank/requests', [BloodBankController::class, 'storeRequest'])->name('bloodbank.requests.store');
        Route::get('/bloodbank/requests/{request}', [BloodBankController::class, 'showRequest'])->name('bloodbank.requests.show');
        Route::get('/bloodbank/requests/{request}/edit', [BloodBankController::class, 'editRequest'])->name('bloodbank.requests.edit');
        Route::put('/bloodbank/requests/{request}', [BloodBankController::class, 'updateRequest'])->name('bloodbank.requests.update');
        Route::delete('/bloodbank/requests/{request}', [BloodBankController::class, 'destroyRequest'])->name('bloodbank.requests.destroy');

        // Blood Bank Workflow
        Route::post('/blood-bank/donations', [\App\Http\Controllers\Hms\BloodDonationController::class, 'store'])->name('bloodbank.donations.store');
        Route::post('/blood-bank/units', [\App\Http\Controllers\Hms\BloodUnitController::class, 'store'])->name('bloodbank.units.store');
        Route::post('/blood-bank/units/{unit}/reserve', [\App\Http\Controllers\Hms\BloodUnitController::class, 'reserve'])->name('bloodbank.units.reserve');
        Route::post('/blood-bank/units/{unit}/issue', [\App\Http\Controllers\Hms\BloodUnitController::class, 'issue'])->name('bloodbank.units.issue');
        Route::post('/blood-bank/crossmatch', [\App\Http\Controllers\Hms\CrossmatchController::class, 'store'])->name('bloodbank.crossmatch.store');
        Route::post('/blood-bank/crossmatch/{crossmatch}/result', [\App\Http\Controllers\Hms\CrossmatchController::class, 'result'])->name('bloodbank.crossmatch.result');
        Route::post('/blood-bank/transfusions', [\App\Http\Controllers\Hms\TransfusionController::class, 'store'])->name('bloodbank.transfusions.store');
        Route::post('/blood-bank/transfusions/{transfusion}/complete', [\App\Http\Controllers\Hms\TransfusionController::class, 'complete'])->name('bloodbank.transfusions.complete');
        Route::post('/blood-bank/transfusions/{transfusion}/reaction', [\App\Http\Controllers\Hms\TransfusionController::class, 'recordReaction'])->name('bloodbank.transfusions.reaction');

        // Credentialing (G073-G074)
        Route::get('/credentialing', [\App\Http\Controllers\Hms\CredentialingController::class, 'index'])->name('credentialing.index');
        Route::get('/credentialing/qualifications', [\App\Http\Controllers\Hms\CredentialingController::class, 'qualifications'])->name('credentialing.qualifications');
        Route::post('/credentialing/qualifications', [\App\Http\Controllers\Hms\CredentialingController::class, 'addQualification'])->name('credentialing.qualifications.store');
        Route::get('/credentialing/licences', [\App\Http\Controllers\Hms\LicenceController::class, 'index'])->name('credentialing.licences.index');
        Route::post('/credentialing/licences', [\App\Http\Controllers\Hms\LicenceController::class, 'store'])->name('credentialing.licences.store');
        Route::get('/credentialing/licences/expiring', [\App\Http\Controllers\Hms\LicenceController::class, 'checkExpiry'])->name('credentialing.licences.expiring');
        Route::get('/credentialing/privileges', [\App\Http\Controllers\Hms\PrivilegeController::class, 'index'])->name('credentialing.privileges.index');
        Route::post('/credentialing/privileges', [\App\Http\Controllers\Hms\PrivilegeController::class, 'grant'])->name('credentialing.privileges.grant');
        Route::post('/credentialing/privileges/{privilege}/revoke', [\App\Http\Controllers\Hms\PrivilegeController::class, 'revoke'])->name('credentialing.privileges.revoke');
        Route::get('/credentialing/oncall', [\App\Http\Controllers\Hms\OncallScheduleController::class, 'index'])->name('credentialing.oncall.index');
        Route::post('/credentialing/oncall', [\App\Http\Controllers\Hms\OncallScheduleController::class, 'store'])->name('credentialing.oncall.store');
        Route::get('/credentialing/cme', [\App\Http\Controllers\Hms\CmeController::class, 'index'])->name('credentialing.cme.index');
        Route::post('/credentialing/cme', [\App\Http\Controllers\Hms\CmeController::class, 'store'])->name('credentialing.cme.store');

        // Ambulance & Emergency
        Route::get('/ambulance', [AmbulanceController::class, 'index'])->name('ambulance.index');
        Route::get('/ambulance/calls', [AmbulanceController::class, 'calls'])->name('ambulance.calls');
        Route::get('/ambulance/emergency', [AmbulanceController::class, 'emergency'])->name('ambulance.emergency');
        Route::get('/ambulance/create-ambulance', [AmbulanceController::class, 'createAmbulance'])->name('ambulance.create-ambulance');
        Route::post('/ambulance/ambulances', [AmbulanceController::class, 'storeAmbulance'])->name('ambulance.store-ambulance');
        Route::get('/ambulance/ambulances/{ambulance}', [AmbulanceController::class, 'showAmbulance'])->name('ambulance.show-ambulance');
        Route::get('/ambulance/ambulances/{ambulance}/edit', [AmbulanceController::class, 'editAmbulance'])->name('ambulance.edit-ambulance');
        Route::put('/ambulance/ambulances/{ambulance}', [AmbulanceController::class, 'updateAmbulance'])->name('ambulance.update-ambulance');
        Route::delete('/ambulance/ambulances/{ambulance}', [AmbulanceController::class, 'destroyAmbulance'])->name('ambulance.destroy-ambulance');
        Route::get('/ambulance/create-call', [AmbulanceController::class, 'createCall'])->name('ambulance.create-call');
        Route::post('/ambulance/calls', [AmbulanceController::class, 'storeCall'])->name('ambulance.store-call');
        Route::get('/ambulance/calls/{call}', [AmbulanceController::class, 'showCall'])->name('ambulance.show-call');
        Route::get('/ambulance/calls/{call}/edit', [AmbulanceController::class, 'editCall'])->name('ambulance.edit-call');
        Route::put('/ambulance/calls/{call}', [AmbulanceController::class, 'updateCall'])->name('ambulance.update-call');
        Route::delete('/ambulance/calls/{call}', [AmbulanceController::class, 'destroyCall'])->name('ambulance.destroy-call');
        Route::get('/ambulance/create-emergency', [AmbulanceController::class, 'createEmergency'])->name('ambulance.create-emergency');
        Route::post('/ambulance/emergency', [AmbulanceController::class, 'storeEmergency'])->name('ambulance.store-emergency');
        Route::get('/ambulance/emergency/{emergency}', [AmbulanceController::class, 'showEmergency'])->name('ambulance.show-emergency');
        Route::get('/ambulance/emergency/{emergency}/assessment', [\App\Http\Controllers\Hms\EmergencyAssessmentController::class, 'show'])->name('ambulance.emergency-assessment');
        Route::get('/ambulance/emergency/{emergency}/edit', [AmbulanceController::class, 'editEmergency'])->name('ambulance.edit-emergency');
        Route::put('/ambulance/emergency/{emergency}', [AmbulanceController::class, 'updateEmergency'])->name('ambulance.update-emergency');
        Route::delete('/ambulance/emergency/{emergency}', [AmbulanceController::class, 'destroyEmergency'])->name('ambulance.destroy-emergency');

        // Ambulance Completion (G045-G046)
        Route::post('/ambulance/crews', [\App\Http\Controllers\Hms\AmbulanceCrewController::class, 'store'])->name('ambulance.crews.store');
        Route::get('/ambulance/crews', [\App\Http\Controllers\Hms\AmbulanceCrewController::class, 'index'])->name('ambulance.crews.index');
        Route::post('/ambulance/trips', [\App\Http\Controllers\Hms\AmbulanceTripController::class, 'store'])->name('ambulance.trips.store');
        Route::post('/ambulance/trips/{trip}/complete', [\App\Http\Controllers\Hms\AmbulanceTripController::class, 'complete'])->name('ambulance.trips.complete');
        Route::get('/ambulance/trips', [\App\Http\Controllers\Hms\AmbulanceTripController::class, 'index'])->name('ambulance.trips.index');
        Route::post('/ambulance/fuel', [\App\Http\Controllers\Hms\AmbulanceFuelController::class, 'store'])->name('ambulance.fuel.store');
        Route::post('/ambulance/maintenance', [\App\Http\Controllers\Hms\AmbulanceMaintenanceController::class, 'store'])->name('ambulance.maintenance.store');
        Route::get('/ambulance/maintenance', [\App\Http\Controllers\Hms\AmbulanceMaintenanceController::class, 'index'])->name('ambulance.maintenance.index');
        Route::post('/ambulance/trips/{trip}/handover', [\App\Http\Controllers\Hms\PatientHandoverController::class, 'store'])->name('ambulance.trips.handover.store');

        // Emergency Completion (G011-G012)
        Route::post('/emergency/{admission}/resuscitation', [\App\Http\Controllers\Hms\ResuscitationController::class, 'store'])->name('emergency.resuscitation.store');
        Route::post('/emergency/{admission}/trauma', [\App\Http\Controllers\Hms\TraumaController::class, 'store'])->name('emergency.trauma.store');
        Route::post('/emergency/{admission}/observations', [\App\Http\Controllers\Hms\ObservationController::class, 'store'])->name('emergency.observations.store');
        Route::post('/emergency/observations/{observation}/discharge', [\App\Http\Controllers\Hms\ObservationController::class, 'discharge'])->name('emergency.observations.discharge');
        Route::post('/emergency/{admission}/disposition', [\App\Http\Controllers\Hms\EmergencyDispositionController::class, 'store'])->name('emergency.disposition.store');

        // Reports & Analytics
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/patients', [ReportsController::class, 'patientReports'])->name('reports.patients')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/revenue', [ReportsController::class, 'revenueReports'])->name('reports.revenue')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/appointments', [ReportsController::class, 'appointmentReports'])->name('reports.appointments')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/financial', [ReportsController::class, 'financialReports'])->name('reports.financial')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/export-patients', [ReportsController::class, 'exportPatients'])->name('reports.export-patients')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/patients/pdf', [ReportsController::class, 'exportPatientsPdf'])->name('reports.patients.pdf')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/revenue/pdf', [ReportsController::class, 'exportRevenuePdf'])->name('reports.revenue.pdf')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        
        // Packages Management
        Route::get('/packages', [PackagesController::class, 'index'])->name('packages.index');
        Route::get('/packages/create', [PackagesController::class, 'create'])->name('packages.create');
        Route::post('/packages', [PackagesController::class, 'store'])->name('packages.store');
        Route::get('/packages/{package}', [PackagesController::class, 'show'])->name('packages.show');
        Route::get('/packages/{package}/edit', [PackagesController::class, 'edit'])->name('packages.edit');
        Route::put('/packages/{package}', [PackagesController::class, 'update'])->name('packages.update');
        Route::delete('/packages/{package}', [PackagesController::class, 'destroy'])->name('packages.destroy');

        // Services Catalog & Price Lists (CRUD)
        Route::middleware('permission:manage price lists|manage services listing|manage packages|view billing|create invoices|manage charges')->group(function () {
            Route::get('/pricing/services', [\App\Http\Controllers\Hms\ServiceController::class, 'index'])->name('pricing.services.index');
            Route::get('/pricing/services/create', [\App\Http\Controllers\Hms\ServiceController::class, 'create'])->name('pricing.services.create');
            Route::post('/pricing/services', [\App\Http\Controllers\Hms\ServiceController::class, 'store'])->name('pricing.services.store');
            Route::get('/pricing/services/{service}', [\App\Http\Controllers\Hms\ServiceController::class, 'show'])->name('pricing.services.show');
            Route::get('/pricing/services/{service}/edit', [\App\Http\Controllers\Hms\ServiceController::class, 'edit'])->name('pricing.services.edit');
            Route::put('/pricing/services/{service}', [\App\Http\Controllers\Hms\ServiceController::class, 'update'])->name('pricing.services.update');
            Route::delete('/pricing/services/{service}', [\App\Http\Controllers\Hms\ServiceController::class, 'destroy'])->name('pricing.services.destroy');
            Route::get('/pricing/services/{service}/prices', [\App\Http\Controllers\Hms\ServiceController::class, 'getPrices'])->name('pricing.services.prices');

            Route::get('/pricing/price-lists', [\App\Http\Controllers\Hms\PriceListController::class, 'index'])->name('pricing.price-lists.index');
            Route::get('/pricing/price-lists/create', [\App\Http\Controllers\Hms\PriceListController::class, 'create'])->name('pricing.price-lists.create');
            Route::post('/pricing/price-lists', [\App\Http\Controllers\Hms\PriceListController::class, 'store'])->name('pricing.price-lists.store');
            Route::get('/pricing/price-lists/{priceList}', [\App\Http\Controllers\Hms\PriceListController::class, 'show'])->name('pricing.price-lists.show');
            Route::get('/pricing/price-lists/{priceList}/edit', [\App\Http\Controllers\Hms\PriceListController::class, 'edit'])->name('pricing.price-lists.edit');
            Route::put('/pricing/price-lists/{priceList}', [\App\Http\Controllers\Hms\PriceListController::class, 'update'])->name('pricing.price-lists.update');
            Route::delete('/pricing/price-lists/{priceList}', [\App\Http\Controllers\Hms\PriceListController::class, 'destroy'])->name('pricing.price-lists.destroy');
        });

        // Hospital Departments (clinical units used to group roles)
        Route::middleware('permission:manage roles|manage permissions|manage user accounts|manage staff profiles')->group(function () {
            Route::get('/hospital-departments', [\App\Http\Controllers\Hms\HospitalDepartmentsController::class, 'index'])->name('hospital-departments.index');
            Route::get('/hospital-departments/create', [\App\Http\Controllers\Hms\HospitalDepartmentsController::class, 'create'])->name('hospital-departments.create');
            Route::post('/hospital-departments', [\App\Http\Controllers\Hms\HospitalDepartmentsController::class, 'store'])->name('hospital-departments.store');
            Route::get('/hospital-departments/{department}', [\App\Http\Controllers\Hms\HospitalDepartmentsController::class, 'show'])->name('hospital-departments.show');
            Route::get('/hospital-departments/{department}/edit', [\App\Http\Controllers\Hms\HospitalDepartmentsController::class, 'edit'])->name('hospital-departments.edit');
            Route::put('/hospital-departments/{department}', [\App\Http\Controllers\Hms\HospitalDepartmentsController::class, 'update'])->name('hospital-departments.update');
            Route::delete('/hospital-departments/{department}', [\App\Http\Controllers\Hms\HospitalDepartmentsController::class, 'destroy'])->name('hospital-departments.destroy');
        });

        // Nurses Management
        Route::get('/nurses', [NursesController::class, 'index'])->name('nurses.index');
        Route::get('/nurses/create', [NursesController::class, 'create'])->name('nurses.create');
        Route::post('/nurses', [NursesController::class, 'store'])->name('nurses.store');
        // Nurse specific routes (must come before /nurses/{nurse})
        Route::get('/nurses/duty-roster', [NursesController::class, 'dutyRoster'])->name('nurses.duty-roster');
        Route::get('/nurses/assign-wards', [NursesController::class, 'assignWards'])->name('nurses.assign-wards');
        Route::get('/nurses/departments', [NursesController::class, 'departments'])->name('nurses.departments');
        Route::post('/nurses/departments', [NursesController::class, 'storeDepartment'])->name('nurses.departments.store');
        Route::get('/nurses/departments/{department}/edit', [NursesController::class, 'editDepartment'])->name('nurses.departments.edit');
        Route::put('/nurses/departments/{department}', [NursesController::class, 'updateDepartment'])->name('nurses.departments.update');
        Route::delete('/nurses/departments/{department}', [NursesController::class, 'destroyDepartment'])->name('nurses.departments.destroy');
        Route::get('/nurses/{nurse}', [NursesController::class, 'show'])->name('nurses.show');
        Route::get('/nurses/{nurse}/edit', [NursesController::class, 'edit'])->name('nurses.edit');
        Route::put('/nurses/{nurse}', [NursesController::class, 'update'])->name('nurses.update');
        Route::delete('/nurses/{nurse}', [NursesController::class, 'destroy'])->name('nurses.destroy');

        // Nursing Management (G023-G024)
        Route::get('/nursing/allocations', [\App\Http\Controllers\Hms\NurseAllocationController::class, 'index'])->name('nursing.allocations.index');
        Route::post('/nursing/allocations', [\App\Http\Controllers\Hms\NurseAllocationController::class, 'store'])->name('nursing.allocations.store');
        Route::post('/nursing/rosters', [\App\Http\Controllers\Hms\DutyRosterController::class, 'store'])->name('nursing.rosters.store');
        Route::post('/nursing/rosters/{roster}/entries', [\App\Http\Controllers\Hms\DutyRosterController::class, 'addEntry'])->name('nursing.rosters.entries.store');
        Route::post('/nursing/rosters/{roster}/publish', [\App\Http\Controllers\Hms\DutyRosterController::class, 'publish'])->name('nursing.rosters.publish');
        Route::post('/nursing/handovers', [\App\Http\Controllers\Hms\ShiftHandoverController::class, 'store'])->name('nursing.handovers.store');
        Route::post('/nursing/handovers/{handover}/acknowledge', [\App\Http\Controllers\Hms\ShiftHandoverController::class, 'acknowledge'])->name('nursing.handovers.acknowledge');
        Route::post('/nursing/procedures', [\App\Http\Controllers\Hms\NursingProcedureController::class, 'store'])->name('nursing.procedures.store');

        // Case Handlers & Social Workers — static /cases routes MUST come before {handler}
        Route::get('/case-handlers', [CaseHandlersController::class, 'index'])->name('case-handlers.index');
        Route::get('/case-handlers/create', [CaseHandlersController::class, 'create'])->name('case-handlers.create');
        Route::post('/case-handlers', [CaseHandlersController::class, 'store'])->name('case-handlers.store');
        Route::get('/case-handlers/cases', [CaseHandlersController::class, 'cases'])->name('case-handlers.cases');
        Route::get('/case-handlers/cases/create', [CaseHandlersController::class, 'createCase'])->name('case-handlers.cases.create');
        Route::post('/case-handlers/cases', [CaseHandlersController::class, 'storeCase'])->name('case-handlers.cases.store');
        Route::get('/case-handlers/cases/{case}', [CaseHandlersController::class, 'showCase'])->name('case-handlers.cases.show');
        Route::get('/case-handlers/cases/{case}/edit', [CaseHandlersController::class, 'editCase'])->name('case-handlers.cases.edit');
        Route::put('/case-handlers/cases/{case}', [CaseHandlersController::class, 'updateCase'])->name('case-handlers.cases.update');
        Route::delete('/case-handlers/cases/{case}', [CaseHandlersController::class, 'destroyCase'])->name('case-handlers.cases.destroy');
        Route::get('/case-handlers/{handler}', [CaseHandlersController::class, 'show'])->name('case-handlers.show');
        Route::get('/case-handlers/{handler}/edit', [CaseHandlersController::class, 'edit'])->name('case-handlers.edit');
        Route::put('/case-handlers/{handler}', [CaseHandlersController::class, 'update'])->name('case-handlers.update');
        Route::delete('/case-handlers/{handler}', [CaseHandlersController::class, 'destroy'])->name('case-handlers.destroy');
        
        // Birth & Death Reports
        Route::get('/reports/birth', [BirthDeathReportsController::class, 'birthReports'])->name('reports.birth')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/death', [BirthDeathReportsController::class, 'deathReports'])->name('reports.death')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/birth/create', [BirthDeathReportsController::class, 'createBirthReport'])->name('reports.birth.create')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::post('/reports/birth', [BirthDeathReportsController::class, 'storeBirthReport'])->name('reports.birth.store')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/birth/{report}', [BirthDeathReportsController::class, 'showBirthReport'])->name('reports.birth.show')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/birth/{report}/edit', [BirthDeathReportsController::class, 'editBirthReport'])->name('reports.birth.edit')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::put('/reports/birth/{report}', [BirthDeathReportsController::class, 'updateBirthReport'])->name('reports.birth.update')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::delete('/reports/birth/{report}', [BirthDeathReportsController::class, 'destroyBirthReport'])->name('reports.birth.destroy')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/death/create', [BirthDeathReportsController::class, 'createDeathReport'])->name('reports.death.create')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::post('/reports/death', [BirthDeathReportsController::class, 'storeDeathReport'])->name('reports.death.store')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/death/{report}', [BirthDeathReportsController::class, 'showDeathReport'])->name('reports.death.show')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/death/{report}/edit', [BirthDeathReportsController::class, 'editDeathReport'])->name('reports.death.edit')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::put('/reports/death/{report}', [BirthDeathReportsController::class, 'updateDeathReport'])->name('reports.death.update')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::delete('/reports/death/{report}', [BirthDeathReportsController::class, 'destroyDeathReport'])->name('reports.death.destroy')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/birth-reports/{report}', [BirthDeathReportsController::class, 'showBirthReport'])->name('birth-reports.show')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/birth-reports/{report}/edit', [BirthDeathReportsController::class, 'editBirthReport'])->name('birth-reports.edit')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::put('/birth-reports/{report}', [BirthDeathReportsController::class, 'updateBirthReport'])->name('birth-reports.update')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::delete('/birth-reports/{report}', [BirthDeathReportsController::class, 'destroyBirthReport'])->name('birth-reports.destroy')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/birth-reports/{report}/certificate', [BirthDeathReportsController::class, 'birthCertificate'])->name('birth-reports.certificate')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/death-reports/{report}', [BirthDeathReportsController::class, 'showDeathReport'])->name('death-reports.show')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/death-reports/{report}/edit', [BirthDeathReportsController::class, 'editDeathReport'])->name('death-reports.edit')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::put('/death-reports/{report}', [BirthDeathReportsController::class, 'updateDeathReport'])->name('death-reports.update')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::delete('/death-reports/{report}', [BirthDeathReportsController::class, 'destroyDeathReport'])->name('death-reports.destroy')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/death-reports/{report}/certificate', [BirthDeathReportsController::class, 'deathCertificate'])->name('death-reports.certificate')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::post('/death-reports/{report}/transfer-to-mortuary', [BirthDeathReportsController::class, 'transferToMortuary'])->name('death-reports.transfer-to-mortuary')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');

        // TB Clinic (M19)
        Route::prefix('tb')->name('tb.')->group(function () {
            Route::post('/screenings', [\App\Http\Controllers\Hms\TbScreeningController::class, 'store'])->name('screenings.store');
            Route::post('/cases', [\App\Http\Controllers\Hms\TbCaseController::class, 'store'])->name('cases.store');
            Route::get('/cases', [\App\Http\Controllers\Hms\TbCaseController::class, 'index'])->name('cases.index');
            Route::put('/cases/{case}', [\App\Http\Controllers\Hms\TbCaseController::class, 'update'])->name('cases.update');
            Route::post('/cases/{case}/treatment', [\App\Http\Controllers\Hms\TbTreatmentController::class, 'store'])->name('cases.treatment.store');
            Route::post('/treatment/{treatment}/complete', [\App\Http\Controllers\Hms\TbTreatmentController::class, 'complete'])->name('treatment.complete');
            Route::post('/treatment/{treatment}/adherence', [\App\Http\Controllers\Hms\TbAdherenceController::class, 'store'])->name('treatment.adherence.store');
            Route::post('/cases/{case}/contacts', [\App\Http\Controllers\Hms\TbContactController::class, 'store'])->name('cases.contacts.store');
            Route::post('/contacts/{contact}/screen', [\App\Http\Controllers\Hms\TbContactController::class, 'screen'])->name('contacts.screen');
        });

        // Mental Health (M27)
        Route::post('/mental-health/assessments', [\App\Http\Controllers\Hms\MhAssessmentController::class, 'store'])->name('mental-health.assessments.store')->middleware('permission:manage mh assessments|manage mh treatment plans|manage counselling sessions');
        Route::get('/mental-health/treatment-plans', [\App\Http\Controllers\Hms\MhTreatmentPlanController::class, 'index'])->name('mental-health.treatment-plans.index')->middleware('permission:manage mh assessments|manage mh treatment plans|manage counselling sessions');
        Route::post('/mental-health/treatment-plans', [\App\Http\Controllers\Hms\MhTreatmentPlanController::class, 'store'])->name('mental-health.treatment-plans.store')->middleware('permission:manage mh assessments|manage mh treatment plans|manage counselling sessions');
        Route::post('/mental-health/counselling', [\App\Http\Controllers\Hms\CounsellingSessionController::class, 'store'])->name('mental-health.counselling.store')->middleware('permission:manage mh assessments|manage mh treatment plans|manage counselling sessions');

        // Social Work (M28)
        Route::post('/social/assessments', [\App\Http\Controllers\Hms\SocialAssessmentController::class, 'store'])->name('social.assessments.store')->middleware('permission:manage social assessments|manage welfare waivers|manage discharge plans');
        Route::post('/social/waivers', [\App\Http\Controllers\Hms\WaiverRequestController::class, 'store'])->name('social.waivers.store')->middleware('permission:manage social assessments|manage welfare waivers|manage discharge plans');
        Route::post('/social/waivers/{waiver}/approve', [\App\Http\Controllers\Hms\WaiverRequestController::class, 'approve'])->name('social.waivers.approve')->middleware('permission:manage social assessments|manage welfare waivers|manage discharge plans');
        Route::post('/social/waivers/{waiver}/reject', [\App\Http\Controllers\Hms\WaiverRequestController::class, 'reject'])->name('social.waivers.reject')->middleware('permission:manage social assessments|manage welfare waivers|manage discharge plans');
        Route::get('/social/discharge-plans', [\App\Http\Controllers\Hms\DischargePlanController::class, 'index'])->name('social.discharge-plans.index')->middleware('permission:manage social assessments|manage welfare waivers|manage discharge plans');
        Route::post('/social/discharge-plans', [\App\Http\Controllers\Hms\DischargePlanController::class, 'store'])->name('social.discharge-plans.store')->middleware('permission:manage social assessments|manage welfare waivers|manage discharge plans');

        // Dental (M22)
        Route::post('/dental/records', [\App\Http\Controllers\Hms\DentalController::class, 'store'])->name('dental.records.store');
        Route::get('/dental/records', [\App\Http\Controllers\Hms\DentalController::class, 'index'])->name('dental.records.index');

        // Ophthalmology (M23)
        Route::post('/ophthalmology/exams', [\App\Http\Controllers\Hms\OphthalmologyController::class, 'store'])->name('ophthalmology.exams.store');
        Route::get('/ophthalmology/exams', [\App\Http\Controllers\Hms\OphthalmologyController::class, 'index'])->name('ophthalmology.exams.index');

        // ENT (M24)
        Route::post('/ent/records', [\App\Http\Controllers\Hms\EntController::class, 'store'])->name('ent.records.store');
        Route::get('/ent/records', [\App\Http\Controllers\Hms\EntController::class, 'index'])->name('ent.records.index');

        // Physio / Occupational Therapy (M25)
        Route::post('/rehab/sessions', [\App\Http\Controllers\Hms\RehabController::class, 'store'])->name('rehab.sessions.store');
        Route::get('/rehab/sessions', [\App\Http\Controllers\Hms\RehabController::class, 'index'])->name('rehab.sessions.index');

        // Nutrition (M26)
        Route::post('/nutrition/records', [\App\Http\Controllers\Hms\NutritionController::class, 'store'])->name('nutrition.records.store')->middleware('permission:manage nutrition records|manage diet orders');
        Route::get('/nutrition/records', [\App\Http\Controllers\Hms\NutritionController::class, 'index'])->name('nutrition.records.index')->middleware('permission:manage nutrition records|manage diet orders');

        // Neonatal Unit (M07)
        Route::post('/neonatal/newborns', [NewbornController::class, 'store'])->name('neonatal.newborns.store');
        Route::get('/neonatal/newborns', [NewbornController::class, 'index'])->name('neonatal.newborns.index');
        Route::post('/neonatal/newborns/{newborn}/assessments', [NeonatalAssessmentController::class, 'store'])->name('neonatal.assessments.store');
        Route::post('/neonatal/newborns/{newborn}/nicu', [NicuController::class, 'store'])->name('neonatal.nicu.store');
        Route::post('/neonatal/nicu/{admission}/discharge', [NicuController::class, 'discharge'])->name('neonatal.nicu.discharge');
        Route::post('/neonatal/newborns/{newborn}/phototherapy', [PhototherapyController::class, 'store'])->name('neonatal.phototherapy.store');
        Route::post('/neonatal/phototherapy/{session}/stop', [PhototherapyController::class, 'stop'])->name('neonatal.phototherapy.stop');
        Route::post('/neonatal/newborns/{newborn}/feeds', [NeonatalFeedController::class, 'store'])->name('neonatal.feeds.store');

        // Maternity, Obstetrics & Gynaecology (M06)
        Route::get('/maternity/pregnancies', [\App\Http\Controllers\Hms\PregnancyController::class, 'index'])->name('maternity.pregnancies.index');
        Route::get('/maternity/pregnancies/{pregnancy}', [\App\Http\Controllers\Hms\PregnancyController::class, 'show'])->name('maternity.pregnancies.show');
        Route::post('/maternity/pregnancies', [\App\Http\Controllers\Hms\PregnancyController::class, 'store'])->name('maternity.pregnancies.store');
        Route::post('/maternity/pregnancies/{pregnancy}/anc', [\App\Http\Controllers\Hms\AncVisitController::class, 'store'])->name('maternity.pregnancies.anc.store');
        Route::post('/maternity/labour', [\App\Http\Controllers\Hms\LabourController::class, 'store'])->name('maternity.labour.store');
        Route::post('/maternity/labour/{labour}/partograph', [\App\Http\Controllers\Hms\LabourController::class, 'partograph'])->name('maternity.labour.partograph.store');
        Route::post('/maternity/deliveries', [\App\Http\Controllers\Hms\DeliveryController::class, 'store'])->name('maternity.deliveries.store');
        Route::post('/maternity/pregnancies/{pregnancy}/postnatal', [\App\Http\Controllers\Hms\PostnatalVisitController::class, 'store'])->name('maternity.pregnancies.postnatal.store');
        Route::post('/maternity/family-planning', [\App\Http\Controllers\Hms\FamilyPlanningController::class, 'store'])->name('maternity.family-planning.store');

        // HIV Testing Services & HIV Care (G037 ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â M18)
        Route::post('/hiv/hts', [\App\Http\Controllers\Hms\HtsEncounterController::class, 'store'])->name('hiv.hts.store')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');
        Route::get('/hiv/hts', [\App\Http\Controllers\Hms\HtsEncounterController::class, 'index'])->name('hiv.hts.index')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');
        Route::post('/hiv/care/enroll', [\App\Http\Controllers\Hms\HivCareController::class, 'enroll'])->name('hiv.care.enroll')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');
        Route::get('/hiv/care', [\App\Http\Controllers\Hms\HivCareController::class, 'index'])->name('hiv.care.index')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');
        Route::post('/hiv/care/{enrollment}/regimen', [\App\Http\Controllers\Hms\ArtController::class, 'startRegimen'])->name('hiv.care.regimen.start')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');
        Route::post('/hiv/care/{enrollment}/regimen/change', [\App\Http\Controllers\Hms\ArtController::class, 'changeRegimen'])->name('hiv.care.regimen.change')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');
        Route::post('/hiv/care/{enrollment}/viral-load', [\App\Http\Controllers\Hms\ViralLoadController::class, 'store'])->name('hiv.care.viral-load.store')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');
        Route::post('/hiv/pep-prep', [\App\Http\Controllers\Hms\PepPrepController::class, 'store'])->name('hiv.pep-prep.store')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');
        Route::post('/hiv/partner-notifications', [\App\Http\Controllers\Hms\PartnerNotificationController::class, 'store'])->name('hiv.partner-notifications.store')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');
        Route::put('/hiv/partner-notifications/{notification}', [\App\Http\Controllers\Hms\PartnerNotificationController::class, 'update'])->name('hiv.partner-notifications.update')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');
        Route::post('/hiv/hei', [\App\Http\Controllers\Hms\HeiController::class, 'store'])->name('hiv.hei.store')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');
        Route::post('/hiv/hei/{hei}/pcr', [\App\Http\Controllers\Hms\HeiController::class, 'recordPcr'])->name('hiv.hei.pcr')->middleware('permission:manage hts encounters|manage hiv care enrollments|manage art regimens|manage tb screens|manage cancer registrations|manage hei records|manage pep prep records');

        // MOH / Regulatory Reports
        Route::get('/moh-reports', [\App\Http\Controllers\Hms\MohReportsController::class, 'index'])->name('moh-reports.index')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/moh-reports/opd-summary', [\App\Http\Controllers\Hms\MohReportsController::class, 'opdSummary'])->name('moh-reports.opd-summary')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/moh-reports/ipd-summary', [\App\Http\Controllers\Hms\MohReportsController::class, 'ipdSummary'])->name('moh-reports.ipd-summary')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/moh-reports/disease-surveillance', [\App\Http\Controllers\Hms\MohReportsController::class, 'diseaseSurveillance'])->name('moh-reports.disease-surveillance')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/moh-reports/maternal-health', [\App\Http\Controllers\Hms\MohReportsController::class, 'maternalHealth'])->name('moh-reports.maternal-health')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/moh-reports/pharmacy-consumption', [\App\Http\Controllers\Hms\MohReportsController::class, 'pharmacyConsumption'])->name('moh-reports.pharmacy-consumption')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/moh-reports/revenue-collection', [\App\Http\Controllers\Hms\MohReportsController::class, 'revenueCollection'])->name('moh-reports.revenue-collection')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/moh-reports/{type}/pdf', [\App\Http\Controllers\Hms\MohReportsController::class, 'generatePdf'])->name('moh-reports.pdf')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        
        // Operation Reports & Surgery
        Route::get('/operations', [OperationReportsController::class, 'index'])->name('operations.index');
        Route::get('/operations/create', [OperationReportsController::class, 'create'])->name('operations.create');
        Route::post('/operations', [OperationReportsController::class, 'store'])->name('operations.store');
        Route::get('/operations/{report}', [OperationReportsController::class, 'show'])->name('operations.show');
        Route::get('/operations/{report}/edit', [OperationReportsController::class, 'edit'])->name('operations.edit');
        Route::put('/operations/{report}', [OperationReportsController::class, 'update'])->name('operations.update');
        Route::delete('/operations/{report}', [OperationReportsController::class, 'destroy'])->name('operations.destroy');
        
        // Patient Diagnosis
        Route::get('/diagnosis/categories', [DiagnosisController::class, 'categories'])->name('diagnosis.categories');
        Route::get('/diagnosis/categories/create', [DiagnosisController::class, 'createCategory'])->name('diagnosis.categories.create');
        Route::post('/diagnosis/categories', [DiagnosisController::class, 'storeCategory'])->name('diagnosis.categories.store');
        Route::get('/diagnosis/categories/{category}', [DiagnosisController::class, 'showCategory'])->name('diagnosis.categories.show');
        Route::get('/diagnosis/categories/{category}/edit', [DiagnosisController::class, 'editCategory'])->name('diagnosis.categories.edit');
        Route::put('/diagnosis/categories/{category}', [DiagnosisController::class, 'updateCategory'])->name('diagnosis.categories.update');
        Route::delete('/diagnosis/categories/{category}', [DiagnosisController::class, 'destroyCategory'])->name('diagnosis.categories.destroy');
        Route::get('/diagnosis/patient-diagnoses', [DiagnosisController::class, 'patientDiagnoses'])->name('diagnosis.patient-diagnoses');
        Route::get('/diagnosis/patient-diagnoses/create', [DiagnosisController::class, 'createDiagnosis'])->name('diagnosis.patient-diagnoses.create');
        Route::post('/diagnosis/patient-diagnoses', [DiagnosisController::class, 'storeDiagnosis'])->name('diagnosis.patient-diagnoses.store');
        Route::get('/diagnosis/patient-diagnoses/{diagnosis}', [DiagnosisController::class, 'showDiagnosis'])->name('diagnosis.patient-diagnoses.show');
        Route::get('/diagnosis/patient-diagnoses/{diagnosis}/edit', [DiagnosisController::class, 'editDiagnosis'])->name('diagnosis.patient-diagnoses.edit');
        Route::put('/diagnosis/patient-diagnoses/{diagnosis}', [DiagnosisController::class, 'updateDiagnosis'])->name('diagnosis.patient-diagnoses.update');
        Route::delete('/diagnosis/patient-diagnoses/{diagnosis}', [DiagnosisController::class, 'destroyDiagnosis'])->name('diagnosis.patient-diagnoses.destroy');
        
        // Staff Management
        Route::get('/staff/receptionists', [StaffManagementController::class, 'receptionists'])->name('staff.receptionists');
        Route::get('/staff/receptionists/create', [StaffManagementController::class, 'createReceptionist'])->name('staff.receptionists.create');
        Route::post('/staff/receptionists', [StaffManagementController::class, 'storeReceptionist'])->name('staff.receptionists.store');
        Route::get('/staff/pharmacists', [StaffManagementController::class, 'pharmacists'])->name('staff.pharmacists');
        Route::get('/staff/pharmacists/create', [StaffManagementController::class, 'createPharmacist'])->name('staff.pharmacists.create');
        Route::post('/staff/pharmacists', [StaffManagementController::class, 'storePharmacist'])->name('staff.pharmacists.store');
        Route::get('/staff/pharmacists/{pharmacist}', [StaffManagementController::class, 'showPharmacist'])->name('staff.pharmacists.show');
        Route::get('/staff/pharmacists/{pharmacist}/edit', [StaffManagementController::class, 'editPharmacist'])->name('staff.pharmacists.edit');
        Route::put('/staff/pharmacists/{pharmacist}', [StaffManagementController::class, 'updatePharmacist'])->name('staff.pharmacists.update');
        Route::get('/staff/lab-technicians', [StaffManagementController::class, 'labTechnicians'])->name('staff.lab-technicians');
        Route::get('/staff/lab-technicians/create', [StaffManagementController::class, 'createLabTechnician'])->name('staff.lab-technicians.create');
        Route::post('/staff/lab-technicians', [StaffManagementController::class, 'storeLabTechnician'])->name('staff.lab-technicians.store');
        Route::get('/staff/lab-technicians/{technician}', [StaffManagementController::class, 'showLabTechnician'])->name('staff.lab-technicians.show');
        Route::get('/staff/lab-technicians/{technician}/edit', [StaffManagementController::class, 'editLabTechnician'])->name('staff.lab-technicians.edit');
        Route::put('/staff/lab-technicians/{technician}', [StaffManagementController::class, 'updateLabTechnician'])->name('staff.lab-technicians.update');
        Route::get('/staff/accountants', [StaffManagementController::class, 'accountants'])->name('staff.accountants');
        Route::get('/staff/accountants/create', [StaffManagementController::class, 'createAccountant'])->name('staff.accountants.create');
        Route::post('/staff/accountants', [StaffManagementController::class, 'storeAccountant'])->name('staff.accountants.store');
        Route::get('/staff/accountants/{accountant}', [StaffManagementController::class, 'showAccountant'])->name('staff.accountants.show');
        Route::get('/staff/accountants/{accountant}/edit', [StaffManagementController::class, 'editAccountant'])->name('staff.accountants.edit');
        Route::put('/staff/accountants/{accountant}', [StaffManagementController::class, 'updateAccountant'])->name('staff.accountants.update');
        
        // Settings & Configuration
        Route::get('/settings', [\App\Http\Controllers\Hms\SettingsController::class, 'index'])->name('settings.index');
        Route::get('/settings/general', [\App\Http\Controllers\Hms\SettingsController::class, 'general'])->name('settings.general');
        Route::post('/settings/general', [\App\Http\Controllers\Hms\SettingsController::class, 'updateGeneral'])->name('settings.general.update');
        Route::get('/settings/branches', [\App\Http\Controllers\Hms\SettingsController::class, 'branches'])->name('settings.branches');
        Route::get('/settings/branches/create', [\App\Http\Controllers\Hms\SettingsController::class, 'createBranch'])->name('settings.branches.create');
        Route::post('/settings/branches', [\App\Http\Controllers\Hms\SettingsController::class, 'storeBranch'])->name('settings.branches.store');
        Route::get('/settings/audit-logs', [\App\Http\Controllers\Hms\SettingsController::class, 'auditLogs'])->name('settings.audit-logs')->middleware('permission:view audit logs|manage break glass events');
       Route::get('/settings/backup', [\App\Http\Controllers\Hms\SettingsController::class, 'backup'])->name('settings.backup')->middleware('permission:manage backups');
       Route::post('/settings/backup/create', [\App\Http\Controllers\Hms\SettingsController::class, 'createBackup'])->name('settings.backup.create')->middleware('permission:manage backups');
       Route::post('/settings/backup/restore', [\App\Http\Controllers\Hms\SettingsController::class, 'restoreBackup'])->name('settings.backup.restore')->middleware('permission:manage backups');
       Route::get('/settings/backup/download/{filename}', [\App\Http\Controllers\Hms\SettingsController::class, 'downloadBackup'])->name('settings.backup.download')->middleware('permission:manage backups');
       Route::delete('/settings/backup/delete/{filename}', [\App\Http\Controllers\Hms\SettingsController::class, 'deleteBackup'])->name('settings.backup.delete')->middleware('permission:manage backups');
       Route::post('/settings/backup/verify/{filename}', [\App\Http\Controllers\Hms\SettingsController::class, 'verifyBackup'])->name('settings.backup.verify')->middleware('permission:manage backups');
        
        // Emergency Contacts Settings
        Route::get('/settings/emergency-contacts', [\App\Http\Controllers\Hms\SettingsController::class, 'emergencyContacts'])->name('settings.emergency-contacts');
        Route::post('/settings/emergency-contacts', [\App\Http\Controllers\Hms\SettingsController::class, 'updateEmergencyContacts'])->name('settings.emergency-contacts.update');
        
        // Dashboard Features
        Route::get('/dashboard/notifications', [\App\Http\Controllers\Hms\NotificationsController::class, 'index'])->name('dashboard.notifications');
        Route::get('/notifications/create', [\App\Http\Controllers\Hms\NotificationsController::class, 'create'])->name('notifications.create');
        Route::post('/notifications', [\App\Http\Controllers\Hms\NotificationsController::class, 'store'])->name('notifications.store');
        Route::get('/notifications/{id}', [\App\Http\Controllers\Hms\NotificationsController::class, 'show'])->name('notifications.show');
        Route::get('/notifications/{id}/edit', [\App\Http\Controllers\Hms\NotificationsController::class, 'edit'])->name('notifications.edit');
        Route::put('/notifications/{id}', [\App\Http\Controllers\Hms\NotificationsController::class, 'update'])->name('notifications.update');
        Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\Hms\NotificationsController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Hms\NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{id}', [\App\Http\Controllers\Hms\NotificationsController::class, 'destroy'])->name('notifications.destroy');
        Route::get('/dashboard/today-summary', [\App\Http\Controllers\Hms\DashboardController::class, 'todaySummary'])->name('dashboard.today-summary');
        Route::get('/dashboard/active-staff', [\App\Http\Controllers\Hms\DashboardController::class, 'activeStaff'])->name('dashboard.active-staff');
        
        // Discharge Summary
        Route::get('/discharge-summary', [\App\Http\Controllers\Hms\DischargeSummaryController::class, 'index'])->name('discharge-summary.index');
        Route::get('/discharge-summary/create', [\App\Http\Controllers\Hms\DischargeSummaryController::class, 'create'])->name('discharge-summary.create');
        Route::post('/discharge-summary', [\App\Http\Controllers\Hms\DischargeSummaryController::class, 'store'])->name('discharge-summary.store');
        Route::get('/discharge-summary/{discharge}', [\App\Http\Controllers\Hms\DischargeSummaryController::class, 'show'])->name('discharge-summary.show');
        Route::get('/discharge-summary/{discharge}/edit', [\App\Http\Controllers\Hms\DischargeSummaryController::class, 'edit'])->name('discharge-summary.edit');
        Route::put('/discharge-summary/{discharge}', [\App\Http\Controllers\Hms\DischargeSummaryController::class, 'update'])->name('discharge-summary.update');
        Route::delete('/discharge-summary/{discharge}', [\App\Http\Controllers\Hms\DischargeSummaryController::class, 'destroy'])->name('discharge-summary.destroy');
        Route::get('/discharge-summary/{discharge}/pdf', [\App\Http\Controllers\Hms\DischargeSummaryController::class, 'generatePdf'])->name('discharge-summary.pdf')->middleware('permission:view patients|sign discharge summaries|manage discharges');
        
        // Doctor Charges
        Route::get('/doctor-charges', [\App\Http\Controllers\Hms\DoctorChargesController::class, 'index'])->name('doctor-charges.index');
        Route::get('/doctor-charges/create', [\App\Http\Controllers\Hms\DoctorChargesController::class, 'create'])->name('doctor-charges.create');
        Route::post('/doctor-charges', [\App\Http\Controllers\Hms\DoctorChargesController::class, 'store'])->name('doctor-charges.store');
        Route::get('/doctor-charges/{id}', [\App\Http\Controllers\Hms\DoctorChargesController::class, 'show'])->name('doctor-charges.show');
        Route::get('/doctor-charges/{doctor}/edit', [\App\Http\Controllers\Hms\DoctorChargesController::class, 'edit'])->name('doctor-charges.edit');
        Route::put('/doctor-charges/{doctor}', [\App\Http\Controllers\Hms\DoctorChargesController::class, 'update'])->name('doctor-charges.update');
        Route::delete('/doctor-charges/{id}', [\App\Http\Controllers\Hms\DoctorChargesController::class, 'destroy'])->name('doctor-charges.destroy');
        
        // Medical History & Vitals
        Route::get('/medical-history', [\App\Http\Controllers\Hms\MedicalHistoryController::class, 'index'])->name('medical-history.index');
        Route::get('/medical-history/create', [\App\Http\Controllers\Hms\MedicalHistoryController::class, 'create'])->name('medical-history.create');
        Route::post('/medical-history', [\App\Http\Controllers\Hms\MedicalHistoryController::class, 'store'])->name('medical-history.store');
        Route::get('/medical-history/{patient}', [\App\Http\Controllers\Hms\MedicalHistoryController::class, 'show'])->name('medical-history.show');
        Route::get('/medical-history/{history}/edit', [\App\Http\Controllers\Hms\MedicalHistoryController::class, 'edit'])->name('medical-history.edit');
        Route::put('/medical-history/{history}', [\App\Http\Controllers\Hms\MedicalHistoryController::class, 'update'])->name('medical-history.update');
        Route::delete('/medical-history/{history}', [\App\Http\Controllers\Hms\MedicalHistoryController::class, 'destroy'])->name('medical-history.destroy');
        
        // Test Categories (Pathology & Radiology)
        Route::get('/test-categories', [\App\Http\Controllers\Hms\TestCategoriesController::class, 'index'])->name('test-categories.index');
        Route::get('/test-categories/create', [\App\Http\Controllers\Hms\TestCategoriesController::class, 'create'])->name('test-categories.create');
        Route::post('/test-categories', [\App\Http\Controllers\Hms\TestCategoriesController::class, 'store'])->name('test-categories.store');
        Route::get('/test-categories/{id}', [\App\Http\Controllers\Hms\TestCategoriesController::class, 'show'])->name('test-categories.show');
        Route::get('/test-categories/{id}/edit', [\App\Http\Controllers\Hms\TestCategoriesController::class, 'edit'])->name('test-categories.edit');
        Route::put('/test-categories/{id}', [\App\Http\Controllers\Hms\TestCategoriesController::class, 'update'])->name('test-categories.update');
        Route::delete('/test-categories/{id}', [\App\Http\Controllers\Hms\TestCategoriesController::class, 'destroy'])->name('test-categories.destroy');
        
        // Investigation Reports
        Route::get('/investigation-reports', [\App\Http\Controllers\Hms\TestCategoriesController::class, 'investigationReports'])->name('investigation-reports.index')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        
        // Blood Bank Stock Levels
        Route::get('/bloodbank/stock-levels', [\App\Http\Controllers\Hms\BloodBankController::class, 'stockLevels'])->name('bloodbank.stock-levels');
        
        // Medicine Categories & Brands
        Route::get('/pharmacy/medicine-categories', [\App\Http\Controllers\Hms\MedicineCategoriesController::class, 'index'])->name('pharmacy.medicine-categories.index');
        Route::get('/pharmacy/medicine-categories/create', [\App\Http\Controllers\Hms\MedicineCategoriesController::class, 'create'])->name('pharmacy.medicine-categories.create');
        Route::post('/pharmacy/medicine-categories', [\App\Http\Controllers\Hms\MedicineCategoriesController::class, 'store'])->name('pharmacy.medicine-categories.store');
        Route::get('/pharmacy/medicine-categories/{category}', [\App\Http\Controllers\Hms\MedicineCategoriesController::class, 'show'])->name('pharmacy.medicine-categories.show');
        Route::get('/pharmacy/medicine-categories/{category}/edit', [\App\Http\Controllers\Hms\MedicineCategoriesController::class, 'edit'])->name('pharmacy.medicine-categories.edit');
        Route::put('/pharmacy/medicine-categories/{category}', [\App\Http\Controllers\Hms\MedicineCategoriesController::class, 'update'])->name('pharmacy.medicine-categories.update');
        Route::delete('/pharmacy/medicine-categories/{category}', [\App\Http\Controllers\Hms\MedicineCategoriesController::class, 'destroy'])->name('pharmacy.medicine-categories.destroy');
        Route::get('/pharmacy/medicine-brands', [\App\Http\Controllers\Hms\MedicineBrandsController::class, 'index'])->name('pharmacy.medicine-brands.index');
        Route::get('/pharmacy/medicine-brands/create', [\App\Http\Controllers\Hms\MedicineBrandsController::class, 'create'])->name('pharmacy.medicine-brands.create');
        Route::post('/pharmacy/medicine-brands', [\App\Http\Controllers\Hms\MedicineBrandsController::class, 'store'])->name('pharmacy.medicine-brands.store');
        Route::get('/pharmacy/medicine-brands/{brand}', [\App\Http\Controllers\Hms\MedicineBrandsController::class, 'show'])->name('pharmacy.medicine-brands.show');
        Route::get('/pharmacy/medicine-brands/{brand}/edit', [\App\Http\Controllers\Hms\MedicineBrandsController::class, 'edit'])->name('pharmacy.medicine-brands.edit');
        Route::put('/pharmacy/medicine-brands/{brand}', [\App\Http\Controllers\Hms\MedicineBrandsController::class, 'update'])->name('pharmacy.medicine-brands.update');
        Route::delete('/pharmacy/medicine-brands/{brand}', [\App\Http\Controllers\Hms\MedicineBrandsController::class, 'destroy'])->name('pharmacy.medicine-brands.destroy');
        
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
        Route::post('/inventory/purchase-orders/{purchaseOrder}/reject', [\App\Http\Controllers\Hms\PurchaseOrdersController::class, 'reject'])->name('inventory.purchase-orders.reject');
        Route::get('/inventory/purchase-orders/{purchaseOrder}/pdf', [\App\Http\Controllers\Hms\PurchaseOrdersController::class, 'generatePdf'])->name('inventory.purchase-orders.pdf')->middleware('permission:manage medicine inventory|export reports');

        // Procurement ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â RFQ Management (G064-G065)
        Route::get('/procurement/rfqs', [\App\Http\Controllers\Hms\RfqController::class, 'index'])->name('procurement.rfqs.index');
        Route::post('/procurement/rfqs', [\App\Http\Controllers\Hms\RfqController::class, 'store'])->name('procurement.rfqs.store');
        Route::post('/procurement/rfqs/{rfq}/close', [\App\Http\Controllers\Hms\RfqController::class, 'close'])->name('procurement.rfqs.close');
        Route::post('/procurement/rfqs/{rfq}/quotations', [\App\Http\Controllers\Hms\QuotationController::class, 'store'])->name('procurement.quotations.store');
        Route::post('/procurement/quotations/{quotation}/accept', [\App\Http\Controllers\Hms\QuotationController::class, 'accept'])->name('procurement.quotations.accept');
        Route::post('/procurement/quotations/{quotation}/reject', [\App\Http\Controllers\Hms\QuotationController::class, 'reject'])->name('procurement.quotations.reject');

        // Procurement ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â Supplier Invoices (G064-G065)
        Route::get('/procurement/supplier-invoices', [\App\Http\Controllers\Hms\SupplierInvoiceController::class, 'index'])->name('procurement.supplier-invoices.index');
        Route::post('/procurement/supplier-invoices', [\App\Http\Controllers\Hms\SupplierInvoiceController::class, 'store'])->name('procurement.supplier-invoices.store');
        Route::post('/procurement/supplier-invoices/{invoice}/verify', [\App\Http\Controllers\Hms\SupplierInvoiceController::class, 'verify'])->name('procurement.supplier-invoices.verify');

        // Stock Issues (G064-G065)
        // Inventory / store issues live with stores routes above
        // Stock Take Sheet
        Route::get('/inventory/stock-take', [InventoryController::class, 'stockTake'])->name('inventory.stock-take');
        
        // Stock Movements
        Route::get('/inventory/stock-movements', [\App\Http\Controllers\Hms\StockMovementsController::class, 'index'])->name('inventory.stock-movements.index');
        Route::get('/inventory/stock-movements/create', [\App\Http\Controllers\Hms\StockMovementsController::class, 'create'])->name('inventory.stock-movements.create');
        Route::post('/inventory/stock-movements', [\App\Http\Controllers\Hms\StockMovementsController::class, 'store'])->name('inventory.stock-movements.store');
        Route::get('/inventory/stock-movements/{stockMovement}', [\App\Http\Controllers\Hms\StockMovementsController::class, 'show'])->name('inventory.stock-movements.show');
        Route::get('/inventory/stock-movements/{stockMovement}/edit', [\App\Http\Controllers\Hms\StockMovementsController::class, 'edit'])->name('inventory.stock-movements.edit');
        Route::put('/inventory/stock-movements/{stockMovement}', [\App\Http\Controllers\Hms\StockMovementsController::class, 'update'])->name('inventory.stock-movements.update');
        Route::delete('/inventory/stock-movements/{stockMovement}', [\App\Http\Controllers\Hms\StockMovementsController::class, 'destroy'])->name('inventory.stock-movements.destroy');
        Route::post('/inventory/stock-movements/receive', [\App\Http\Controllers\Hms\StockMovementsController::class, 'receiveStock'])->name('inventory.stock-movements.receive');
        Route::get('/inventory/stock-report', [\App\Http\Controllers\Hms\StockMovementsController::class, 'stockReport'])->name('inventory.stock-report');
        
        // Legacy inventory routes
        Route::get('/inventory/categories', [\App\Http\Controllers\Hms\InventoryManagementController::class, 'categories'])->name('inventory.categories');
        Route::post('/inventory/categories', [\App\Http\Controllers\Hms\InventoryManagementController::class, 'storeCategory'])->name('inventory.categories.store');
        Route::put('/inventory/categories/{id}', [\App\Http\Controllers\Hms\InventoryManagementController::class, 'updateCategory'])->name('inventory.categories.update');
        Route::delete('/inventory/categories/{id}', [\App\Http\Controllers\Hms\InventoryManagementController::class, 'deleteCategory'])->name('inventory.categories.delete');
        Route::get('/inventory/expiry-alerts', [\App\Http\Controllers\Hms\InventoryManagementController::class, 'expiryAlerts'])->name('inventory.expiry-alerts');
        
        // Finance - Billing
        Route::get('/billing/receipts', [\App\Http\Controllers\Hms\BillingController::class, 'receipts'])->name('billing.receipts')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        Route::get('/billing/payment-reports', [\App\Http\Controllers\Hms\BillingController::class, 'paymentReports'])->name('billing.payment-reports')->middleware('permission:create invoices|edit invoices|add payments|add refunds|view invoices|view billing|view payments|manage advance payments|manage payment methods|manage charges|manage discounts');
        
        // Advance Payments
        Route::get('/advance-payments', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'index'])->name('advance-payments.index');
        Route::get('/advance-payments/create', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'create'])->name('advance-payments.create');
        Route::post('/advance-payments', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'store'])->name('advance-payments.store');
        Route::get('/advance-payments/deposits', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'deposits'])->name('advance-payments.deposits');
        Route::get('/advance-payments/refunds', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'refunds'])->name('advance-payments.refunds');
        Route::post('/advance-payments/refunds', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'processRefund'])->name('advance-payments.process-refund');
        Route::get('/advance-payments/{advancePayment}', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'show'])->name('advance-payments.show');
        Route::get('/advance-payments/{advancePayment}/edit', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'edit'])->name('advance-payments.edit');
        Route::put('/advance-payments/{advancePayment}', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'update'])->name('advance-payments.update');
        Route::delete('/advance-payments/{advancePayment}', [\App\Http\Controllers\Hms\AdvancePaymentsController::class, 'destroy'])->name('advance-payments.destroy');
        
        // Finance - Accounts Management
        Route::get('/finance/accounts', [\App\Http\Controllers\Hms\AccountsController::class, 'index'])->name('finance.accounts.index');
        Route::get('/finance/accounts/create', [\App\Http\Controllers\Hms\AccountsController::class, 'create'])->name('finance.accounts.create');
        Route::post('/finance/accounts', [\App\Http\Controllers\Hms\AccountsController::class, 'store'])->name('finance.accounts.store');
        Route::get('/finance/accounts/{account}', [\App\Http\Controllers\Hms\AccountsController::class, 'show'])->name('finance.accounts.show');
        Route::get('/finance/accounts/{account}/edit', [\App\Http\Controllers\Hms\AccountsController::class, 'edit'])->name('finance.accounts.edit');
        Route::put('/finance/accounts/{account}', [\App\Http\Controllers\Hms\AccountsController::class, 'update'])->name('finance.accounts.update');
        Route::delete('/finance/accounts/{account}', [\App\Http\Controllers\Hms\AccountsController::class, 'destroy'])->name('finance.accounts.destroy');

        // Bank Reconciliation
        Route::get('/finance/bank-reconciliations', [\App\Http\Controllers\Hms\BankReconciliationController::class, 'index'])->name('finance.bank-reconciliations.index')->middleware('permission:view financial reports|generate financial reports|view invoices|manage bank accounts|create invoices');
        Route::get('/finance/bank-reconciliations/create', [\App\Http\Controllers\Hms\BankReconciliationController::class, 'create'])->name('finance.bank-reconciliations.create')->middleware('permission:view financial reports|generate financial reports|view invoices|manage bank accounts|create invoices');
        Route::post('/finance/bank-reconciliations', [\App\Http\Controllers\Hms\BankReconciliationController::class, 'store'])->name('finance.bank-reconciliations.store')->middleware('permission:view financial reports|generate financial reports|view invoices|manage bank accounts|create invoices');
        Route::get('/finance/bank-reconciliations/{reconciliation}', [\App\Http\Controllers\Hms\BankReconciliationController::class, 'show'])->name('finance.bank-reconciliations.show')->middleware('permission:view financial reports|generate financial reports|view invoices|manage bank accounts|create invoices');
        Route::post('/finance/bank-reconciliations/{reconciliation}/reconciled', [\App\Http\Controllers\Hms\BankReconciliationController::class, 'markReconciled'])->name('finance.bank-reconciliations.mark-reconciled')->middleware('permission:view financial reports|generate financial reports|view invoices|manage bank accounts|create invoices');
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
        Route::get('/finance/profit-loss/pdf', [\App\Http\Controllers\Hms\FinanceController::class, 'profitLossPdf'])->name('finance.profit-loss.pdf')->middleware('permission:view financial reports|generate financial reports');
        Route::get('/finance/balance-sheet', [\App\Http\Controllers\Hms\FinanceController::class, 'balanceSheet'])->name('finance.balance-sheet');
        Route::get('/finance/cash-flow', [\App\Http\Controllers\Hms\FinanceController::class, 'cashFlow'])->name('finance.cash-flow');

        // Finance - Journal Entries (Double-Entry Bookkeeping)
        Route::get('/journal', [\App\Http\Controllers\Hms\JournalController::class, 'index'])->name('journal.index');
        Route::post('/journal', [\App\Http\Controllers\Hms\JournalController::class, 'store'])->name('journal.store');
        Route::get('/journal/{entry}', [\App\Http\Controllers\Hms\JournalController::class, 'show'])->name('journal.show');
        Route::post('/journal/{entry}/post', [\App\Http\Controllers\Hms\JournalController::class, 'post'])->name('journal.post');
        Route::post('/journal/{entry}/reverse', [\App\Http\Controllers\Hms\JournalController::class, 'reverse'])->name('journal.reverse');
        Route::post('/finance/fiscal-periods/{period}/close', [\App\Http\Controllers\Hms\JournalController::class, 'closePeriod'])->name('finance.fiscal-periods.close');

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
        
        // HR - Designations & Documents
        Route::get('/hr/designations', [\App\Http\Controllers\Hms\DesignationsController::class, 'index'])->name('hr.designations.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/designations/create', [\App\Http\Controllers\Hms\DesignationsController::class, 'create'])->name('hr.designations.create')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/designations', [\App\Http\Controllers\Hms\DesignationsController::class, 'store'])->name('hr.designations.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/designations/{designation}', [\App\Http\Controllers\Hms\DesignationsController::class, 'show'])->name('hr.designations.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/designations/{designation}/edit', [\App\Http\Controllers\Hms\DesignationsController::class, 'edit'])->name('hr.designations.edit')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::put('/hr/designations/{designation}', [\App\Http\Controllers\Hms\DesignationsController::class, 'update'])->name('hr.designations.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::delete('/hr/designations/{designation}', [\App\Http\Controllers\Hms\DesignationsController::class, 'destroy'])->name('hr.designations.destroy')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        // HR - Performance Appraisals
        Route::get('/hr/appraisals', [PerformanceAppraisalsController::class, 'index'])->name('hr.appraisals.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/appraisals/create', [PerformanceAppraisalsController::class, 'create'])->name('hr.appraisals.create')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/appraisals', [PerformanceAppraisalsController::class, 'store'])->name('hr.appraisals.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/appraisals/{appraisal}', [PerformanceAppraisalsController::class, 'show'])->name('hr.appraisals.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/appraisals/{appraisal}/edit', [PerformanceAppraisalsController::class, 'edit'])->name('hr.appraisals.edit')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::put('/hr/appraisals/{appraisal}', [PerformanceAppraisalsController::class, 'update'])->name('hr.appraisals.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::delete('/hr/appraisals/{appraisal}', [PerformanceAppraisalsController::class, 'destroy'])->name('hr.appraisals.destroy')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        Route::get('/hr/documents', [\App\Http\Controllers\Hms\HrDocumentsController::class, 'index'])->name('hr.documents.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/documents/create', [\App\Http\Controllers\Hms\HrDocumentsController::class, 'create'])->name('hr.documents.create')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/document-types', [\App\Http\Controllers\Hms\HrDocumentsController::class, 'types'])->name('hr.document-types')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/documents', [\App\Http\Controllers\Hms\HrDocumentsController::class, 'store'])->name('hr.documents.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/documents/{document}', [\App\Http\Controllers\Hms\HrDocumentsController::class, 'show'])->name('hr.documents.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/documents/{document}/edit', [\App\Http\Controllers\Hms\HrDocumentsController::class, 'edit'])->name('hr.documents.edit')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::put('/hr/documents/{document}', [\App\Http\Controllers\Hms\HrDocumentsController::class, 'update'])->name('hr.documents.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::delete('/hr/documents/{document}', [\App\Http\Controllers\Hms\HrDocumentsController::class, 'destroy'])->name('hr.documents.destroy')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        // HR - Leave Types Management
        Route::resource('hr/leave-types', LeaveTypesController::class)->names('hr.leave-types');
        
        // HR - Recruitment & Onboarding
        Route::resource('hr/job-postings', RecruitmentController::class)->names('hr.job-postings');
        Route::post('/hr/job-postings/{jobPosting}/publish', [RecruitmentController::class, 'publish'])->name('hr.job-postings.publish')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/job-applications', [RecruitmentController::class, 'applications'])->name('hr.job-applications.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/job-applications/{application}', [RecruitmentController::class, 'showApplication'])->name('hr.job-applications.show')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/job-applications/{application}/shortlist', [RecruitmentController::class, 'shortlist'])->name('hr.job-applications.shortlist')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/job-applications/{application}/reject', [RecruitmentController::class, 'reject'])->name('hr.job-applications.reject')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/job-applications/{application}/convert-to-employee', [RecruitmentController::class, 'convertToEmployee'])->name('hr.job-applications.convert')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        // HR - Training & Development
        Route::resource('hr/training-programs', TrainingProgramsController::class)->names('hr.training-programs');
        Route::post('/hr/training-programs/{trainingProgram}/enroll', [TrainingProgramsController::class, 'enroll'])->name('hr.training-programs.enroll')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/training-programs/{trainingProgram}/enrollments', [TrainingProgramsController::class, 'enrollments'])->name('hr.training-programs.enrollments')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/training-enrollments/{enrollment}/complete', [TrainingProgramsController::class, 'markComplete'])->name('hr.training-enrollments.complete')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/training-enrollments/{enrollment}/certificate', [TrainingProgramsController::class, 'issueCertificate'])->name('hr.training-enrollments.certificate')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/training/{enrollment}/certificate', [TrainingProgramsController::class, 'certificate'])->name('hr.training.certificate')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        // HR - Announcements & Notices
        Route::resource('hr/announcements', HrAnnouncementsController::class)->names('hr.announcements');
        
        // HR - Shift Management
        Route::resource('hr/shifts', ShiftsController::class)->names('hr.shifts');
        Route::get('/hr/shifts/{shift}/roster', [ShiftsController::class, 'roster'])->name('hr.shifts.roster')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/employee-shifts', [ShiftsController::class, 'assignShift'])->name('hr.employee-shifts.assign')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/shifts/roster-pdf', [ShiftsController::class, 'rosterPdf'])->name('hr.shifts.roster-pdf')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        // HR - Public Holidays
        Route::resource('hr/public-holidays', PublicHolidaysController::class)->names('hr.public-holidays');
        
        // HR - Reports
        Route::get('/hr/reports', [HrReportsController::class, 'index'])->name('hr.reports.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/reports/employee-list', [HrReportsController::class, 'employeeList'])->name('hr.reports.employee-list')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/reports/leave', [HrReportsController::class, 'leaveReport'])->name('hr.reports.leave')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/reports/attendance', [HrReportsController::class, 'attendanceReport'])->name('hr.reports.attendance')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/reports/payroll-summary', [HrReportsController::class, 'payrollSummary'])->name('hr.reports.payroll-summary')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/reports/headcount-trends', [HrReportsController::class, 'headcountTrends'])->name('hr.reports.headcount-trends')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/reports/attrition', [HrReportsController::class, 'attritionReport'])->name('hr.reports.attrition')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/reports/salary-expense', [HrReportsController::class, 'salaryExpenseAnalysis'])->name('hr.reports.salary-expense')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/reports/training-participation', [HrReportsController::class, 'trainingParticipation'])->name('hr.reports.training-participation')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        // HR - Settings
        Route::get('/hr/settings', [HrSettingsController::class, 'index'])->name('hr.settings.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/settings', [HrSettingsController::class, 'update'])->name('hr.settings.update')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        // ID Card Generation
        Route::get('/patients/{patient}/id-card', [\App\Http\Controllers\Hms\IdCardController::class, 'patientCard'])->name('patients.id-card');
        Route::get('/patients/{patient}/id-card/preview', [\App\Http\Controllers\Hms\IdCardController::class, 'previewPatient'])->name('patients.id-card.preview');
        Route::get('/patients/{patient}/id-card/qr', [\App\Http\Controllers\Hms\IdCardController::class, 'generatePatientQR'])->name('patients.id-card.qr');
        Route::get('/hr/employees/{employee}/id-card', [\App\Http\Controllers\Hms\IdCardController::class, 'employeeCard'])->name('hr.employees.id-card')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/employees/{employee}/id-card/preview', [\App\Http\Controllers\Hms\IdCardController::class, 'previewEmployee'])->name('hr.employees.id-card.preview')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/employees/{employee}/id-card/qr', [\App\Http\Controllers\Hms\IdCardController::class, 'generateEmployeeQR'])->name('hr.employees.id-card.qr')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/id-cards/scan-qr', [\App\Http\Controllers\Hms\IdCardController::class, 'scanQR'])->name('id-cards.scan-qr');
        Route::get('/id-cards/bulk/patients', [\App\Http\Controllers\Hms\IdCardController::class, 'bulkPatientCards'])->name('id-cards.bulk-patients');
        Route::get('/id-cards/bulk/employees', [\App\Http\Controllers\Hms\IdCardController::class, 'bulkEmployeeCards'])->name('id-cards.bulk-employees');
        
        // HR - Employee Import/Export
        Route::get('/hr/employees/export', [EmployeesImportExportController::class, 'export'])->name('hr.employees.export')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/employees/import', [EmployeesImportExportController::class, 'showImport'])->name('hr.employees.import')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/hr/employees/import', [EmployeesImportExportController::class, 'import'])->name('hr.employees.import.store')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/hr/employees/import/template', [EmployeesImportExportController::class, 'downloadTemplate'])->name('hr.employees.import.template')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        // Reports
        Route::get('/reports/billing', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'billingReport'])->name('reports.billing')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/lab', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'labReport'])->name('reports.lab')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/pharmacy', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'pharmacyReport'])->name('reports.pharmacy')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/blood-bank', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'bloodBankReport'])->name('reports.blood-bank')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/bed-occupancy', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'bedOccupancyReport'])->name('reports.bed-occupancy')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/diagnosis', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'diagnosisReport'])->name('reports.diagnosis')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/doctor-performance', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'doctorPerformanceReport'])->name('reports.doctor-performance')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/expense', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'expenseReport'])->name('reports.expense')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::get('/reports/summary', [\App\Http\Controllers\Hms\AnalyticsReportsController::class, 'summaryReports'])->name('reports.summary')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        
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

        // Dashboard Definitions
        Route::get('/dashboard/definitions', [\App\Http\Controllers\Hms\DashboardDefinitionController::class, 'index'])->name('dashboard.definitions.index');
        Route::post('/dashboard/definitions', [\App\Http\Controllers\Hms\DashboardDefinitionController::class, 'store'])->name('dashboard.definitions.store');
        Route::get('/dashboard/definitions/{dashboard}', [\App\Http\Controllers\Hms\DashboardDefinitionController::class, 'show'])->name('dashboard.definitions.show');

        // KPI Management
        Route::get('/dashboard/kpis', [\App\Http\Controllers\Hms\KpiController::class, 'index'])->name('dashboard.kpis.index');
        Route::post('/dashboard/kpis/{kpi}/record', [\App\Http\Controllers\Hms\KpiController::class, 'record'])->name('dashboard.kpis.record');
        Route::get('/dashboard/kpis/{kpi}/trend', [\App\Http\Controllers\Hms\KpiController::class, 'trend'])->name('dashboard.kpis.trend');

        // Saved Reports
        Route::get('/reports/saved', [\App\Http\Controllers\Hms\SavedReportController::class, 'index'])->name('reports.saved.index')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::post('/reports/saved', [\App\Http\Controllers\Hms\SavedReportController::class, 'store'])->name('reports.saved.store')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::post('/reports/saved/{report}/run', [\App\Http\Controllers\Hms\SavedReportController::class, 'run'])->name('reports.saved.run')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');

        // Report Schedules
        Route::post('/reports/schedules', [\App\Http\Controllers\Hms\ReportScheduleController::class, 'store'])->name('reports.schedules.store')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::post('/reports/schedules/{schedule}/pause', [\App\Http\Controllers\Hms\ReportScheduleController::class, 'pause'])->name('reports.schedules.pause')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');
        Route::post('/reports/schedules/{schedule}/resume', [\App\Http\Controllers\Hms\ReportScheduleController::class, 'resume'])->name('reports.schedules.resume')->middleware('permission:export reports|generate patient reports|generate billing reports|generate financial reports|generate birth reports|generate death reports|generate pathology reports|generate operation reports|view reports|manage saved reports|manage report schedules');

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
        Route::get('/reminders/create', [\App\Http\Controllers\Hms\RemindersController::class, 'create'])->name('reminders.create');
        Route::get('/reminders/appointments', [\App\Http\Controllers\Hms\RemindersController::class, 'appointments'])->name('reminders.appointments');
        Route::get('/reminders/payments', [\App\Http\Controllers\Hms\RemindersController::class, 'payments'])->name('reminders.payments');
        Route::post('/reminders', [\App\Http\Controllers\Hms\RemindersController::class, 'store'])->name('reminders.store');
        Route::get('/reminders/{id}', [\App\Http\Controllers\Hms\RemindersController::class, 'show'])->name('reminders.show');
        Route::get('/reminders/{id}/edit', [\App\Http\Controllers\Hms\RemindersController::class, 'edit'])->name('reminders.edit');
        Route::put('/reminders/{id}', [\App\Http\Controllers\Hms\RemindersController::class, 'update'])->name('reminders.update');
        Route::delete('/reminders/{id}', [\App\Http\Controllers\Hms\RemindersController::class, 'destroy'])->name('reminders.destroy');
        
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
        Route::get('/settings/theme/export', [\App\Http\Controllers\Hms\ThemeController::class, 'export'])->name('settings.theme.export')->middleware('permission:manage system settings');
        Route::post('/settings/theme/import', [\App\Http\Controllers\Hms\ThemeController::class, 'import'])->name('settings.theme.import');
        Route::post('/settings/theme/toggle-dark-mode', [\App\Http\Controllers\Hms\ThemeController::class, 'toggleDarkMode'])->name('settings.theme.toggle-dark-mode');
        
        // Daily Summary
        Route::get('/daily-summary', [\App\Http\Controllers\Hms\DailySummaryController::class, 'index'])->name('daily-summary.index');
        Route::post('/daily-summary/generate', [\App\Http\Controllers\Hms\DailySummaryController::class, 'generate'])->name('daily-summary.generate');
        Route::post('/daily-summary/auto-generate', [\App\Http\Controllers\Hms\DailySummaryController::class, 'autoGenerate'])->name('daily-summary.auto-generate');
        
        // EHR Integration
        Route::get('/integration/ehr', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'index'])->name('integration.ehr.index')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/integration/ehr/hl7-config', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'hl7Config'])->name('integration.ehr.hl7-config')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::get('/integration/ehr/fhir-config', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'fhirConfig'])->name('integration.ehr.fhir-config')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/integration/ehr/send-hl7', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'sendHl7Message'])->name('integration.ehr.send-hl7')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/integration/ehr/receive-hl7', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'receiveHl7Message'])->name('integration.ehr.receive-hl7')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/integration/ehr/send-fhir', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'sendFhirResource'])->name('integration.ehr.send-fhir')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        Route::post('/integration/ehr/test-hl7', [\App\Http\Controllers\Hms\EhrIntegrationController::class, 'testHl7Connection'])->name('integration.ehr.test-hl7')->middleware('permission:manage employees|manage employee contracts|manage payrolls|view attendance|manage leave requests|manage recruitment|manage job postings|manage training programs|manage appraisals|manage shift types|manage roster builder|manage staff profiles|view staff profiles|manage payroll exports|manage disciplinary records');
        
        // Integrations
        Route::get('/integrations', [\App\Http\Controllers\Hms\IntegrationsController::class, 'index'])->name('integrations.index');
        Route::get('/integrations/payment-gateways', [\App\Http\Controllers\Hms\IntegrationsController::class, 'paymentGateways'])->name('integrations.payment-gateways');
        Route::get('/integrations/whatsapp', [\App\Http\Controllers\Hms\IntegrationsController::class, 'whatsapp'])->name('integrations.whatsapp');
        Route::get('/integrations/google-calendar', [\App\Http\Controllers\Hms\IntegrationsController::class, 'googleCalendar'])->name('integrations.google-calendar');
        Route::get('/integrations/alerts', [\App\Http\Controllers\Hms\IntegrationsController::class, 'automatedAlerts'])->name('integrations.alerts');
        Route::get('/integrations/data-sync', [\App\Http\Controllers\Hms\IntegrationsController::class, 'dataSync'])->name('integrations.data-sync');
        
        // AI Features
        Route::get('/ai/predictive-analytics', [\App\Http\Controllers\Ai\AiAssistantController::class, 'predictiveAnalytics'])->name('ai.predictive-analytics');
        
        // Oncology Module (M20 / G039)
        Route::prefix('oncology')->name('oncology.')->group(function () {
            Route::get('/registrations', [\App\Http\Controllers\Hms\CancerRegistrationController::class, 'index'])->name('registrations.index');
            Route::post('/registrations', [\App\Http\Controllers\Hms\CancerRegistrationController::class, 'store'])->name('registrations.store');
            Route::get('/plans', [\App\Http\Controllers\Hms\OncologyTreatmentPlanController::class, 'index'])->name('plans.index');
            Route::post('/plans', [\App\Http\Controllers\Hms\OncologyTreatmentPlanController::class, 'store'])->name('plans.store');
            Route::get('/protocols', [\App\Http\Controllers\Hms\ChemoProtocolController::class, 'index'])->name('protocols.index');
            Route::post('/protocols', [\App\Http\Controllers\Hms\ChemoProtocolController::class, 'store'])->name('protocols.store');
            Route::post('/plans/{plan}/cycles', [\App\Http\Controllers\Hms\ChemoCycleController::class, 'store'])->name('plans.cycles.store');
            Route::post('/cycles/{cycle}/complete', [\App\Http\Controllers\Hms\ChemoCycleController::class, 'complete'])->name('cycles.complete');
            Route::post('/cycles/{cycle}/infusions', [\App\Http\Controllers\Hms\ChemoInfusionController::class, 'store'])->name('cycles.infusions.store');
            Route::post('/infusions/{infusion}/complete', [\App\Http\Controllers\Hms\ChemoInfusionController::class, 'complete'])->name('infusions.complete');
            Route::post('/adverse-events', [\App\Http\Controllers\Hms\AdverseEventController::class, 'store'])->name('adverse-events.store');
        });

        // Infection Prevention & Control (G049)
        Route::prefix('ipc')->name('ipc.')->group(function () {
            Route::get('/hai-surveillance', [\App\Http\Controllers\Hms\HaiSurveillanceController::class, 'index'])->name('hai-surveillance.index');
            Route::post('/hai-surveillance', [\App\Http\Controllers\Hms\HaiSurveillanceController::class, 'store'])->name('hai-surveillance.store');
            Route::post('/isolation', [\App\Http\Controllers\Hms\IsolationController::class, 'store'])->name('isolation.store');
            Route::post('/isolation/{isolation}/discharge', [\App\Http\Controllers\Hms\IsolationController::class, 'discharge'])->name('isolation.discharge');
            Route::post('/hand-hygiene', [\App\Http\Controllers\Hms\HandHygieneController::class, 'store'])->name('hand-hygiene.store');
            Route::get('/audits', [\App\Http\Controllers\Hms\IpcAuditController::class, 'index'])->name('audits.index');
            Route::post('/audits', [\App\Http\Controllers\Hms\IpcAuditController::class, 'store'])->name('audits.store');
            Route::post('/outbreak-investigations', [\App\Http\Controllers\Hms\OutbreakInvestigationController::class, 'store'])->name('outbreak-investigations.store');
            Route::put('/outbreak-investigations/{investigation}', [\App\Http\Controllers\Hms\OutbreakInvestigationController::class, 'update'])->name('outbreak-investigations.update');
            Route::post('/antibiotic-usage', [\App\Http\Controllers\Hms\AntibioticUsageController::class, 'store'])->name('antibiotic-usage.store');
        });

        // Training & Research (M49 / G083-G084)
        Route::post('/training/trainees', [\App\Http\Controllers\Hms\TraineeController::class, 'store'])->name('training.trainees.store');
        Route::get('/training/trainees', [\App\Http\Controllers\Hms\TraineeController::class, 'index'])->name('training.trainees.index');
        Route::post('/training/trainees/{trainee}/assessments', [\App\Http\Controllers\Hms\TraineeAssessmentController::class, 'store'])->name('training.trainees.assessments.store');
        Route::post('/research/projects', [\App\Http\Controllers\Hms\ResearchProjectController::class, 'store'])->name('research.projects.store');
        Route::get('/research/projects', [\App\Http\Controllers\Hms\ResearchProjectController::class, 'index'])->name('research.projects.index');
        Route::put('/research/projects/{project}', [\App\Http\Controllers\Hms\ResearchProjectController::class, 'update'])->name('research.projects.update');
        Route::post('/research/projects/{project}/ethics', [\App\Http\Controllers\Hms\EthicsApprovalController::class, 'store'])->name('research.projects.ethics.store');
        Route::put('/research/ethics/{approval}', [\App\Http\Controllers\Hms\EthicsApprovalController::class, 'update'])->name('research.ethics.update');
        Route::post('/research/publications', [\App\Http\Controllers\Hms\PublicationController::class, 'store'])->name('research.publications.store');
        Route::get('/research/publications', [\App\Http\Controllers\Hms\PublicationController::class, 'index'])->name('research.publications.index');

        // Quality Assurance, Feedback & Complaints (M50 / G085)
        Route::prefix('quality')->name('quality.')->group(function () {
            Route::get('/complaints', [\App\Http\Controllers\Hms\ComplaintController::class, 'index'])->name('complaints.index');
            Route::post('/complaints', [\App\Http\Controllers\Hms\ComplaintController::class, 'store'])->name('complaints.store');
            Route::post('/complaints/{complaint}/resolve', [\App\Http\Controllers\Hms\ComplaintController::class, 'resolve'])->name('complaints.resolve');
            Route::get('/incidents', [\App\Http\Controllers\Hms\IncidentController::class, 'index'])->name('incidents.index');
            Route::post('/incidents', [\App\Http\Controllers\Hms\IncidentController::class, 'store'])->name('incidents.store');
            Route::post('/incidents/{incident}/investigate', [\App\Http\Controllers\Hms\IncidentController::class, 'investigate'])->name('incidents.investigate');
            Route::get('/mortality-reviews', [\App\Http\Controllers\Hms\MortalityReviewController::class, 'index'])->name('mortality-reviews.index');
            Route::post('/mortality-reviews', [\App\Http\Controllers\Hms\MortalityReviewController::class, 'store'])->name('mortality-reviews.store');
            Route::get('/indicators', [\App\Http\Controllers\Hms\QualityIndicatorController::class, 'index'])->name('indicators.index');
            Route::post('/indicators', [\App\Http\Controllers\Hms\QualityIndicatorController::class, 'store'])->name('indicators.store');
            Route::post('/indicators/{indicator}/values', [\App\Http\Controllers\Hms\IndicatorValueController::class, 'store'])->name('indicators.values.store');
            Route::post('/corrective-actions', [\App\Http\Controllers\Hms\CorrectiveActionController::class, 'store'])->name('corrective-actions.store');
            Route::post('/corrective-actions/{action}/complete', [\App\Http\Controllers\Hms\CorrectiveActionController::class, 'complete'])->name('corrective-actions.complete');
        });

        // Linen & Laundry Management (G052-G053)
        Route::post('/linen/records', [\App\Http\Controllers\Hms\LinenController::class, 'store'])->name('linen.records.store');
        Route::post('/linen/records/{record}/return', [\App\Http\Controllers\Hms\LinenController::class, 'returnLinen'])->name('linen.records.return');
        Route::get('/linen/records', [\App\Http\Controllers\Hms\LinenController::class, 'index'])->name('linen.records.index');
        Route::post('/linen/batches', [\App\Http\Controllers\Hms\LaundryBatchController::class, 'store'])->name('linen.batches.store');
        Route::post('/linen/batches/{batch}/complete', [\App\Http\Controllers\Hms\LaundryBatchController::class, 'complete'])->name('linen.batches.complete');

        // Kitchen & Catering Management (G052-G053)
        Route::post('/kitchen/meals', [\App\Http\Controllers\Hms\MealOrderController::class, 'store'])->name('kitchen.meals.store');
        Route::post('/kitchen/meals/{meal}/deliver', [\App\Http\Controllers\Hms\MealOrderController::class, 'deliver'])->name('kitchen.meals.deliver');
        Route::get('/kitchen/meals', [\App\Http\Controllers\Hms\MealOrderController::class, 'index'])->name('kitchen.meals.index');
        Route::post('/kitchen/inventory', [\App\Http\Controllers\Hms\KitchenInventoryController::class, 'store'])->name('kitchen.inventory.store');
        Route::get('/kitchen/inventory', [\App\Http\Controllers\Hms\KitchenInventoryController::class, 'index'])->name('kitchen.inventory.index');
        Route::post('/kitchen/inventory/{item}/restock', [\App\Http\Controllers\Hms\KitchenInventoryController::class, 'restock'])->name('kitchen.inventory.restock');

        // Global Search
        Route::get('/search', [\App\Http\Controllers\Hms\GlobalSearchController::class, 'search'])->name('global-search');

        // Communications & Campaigns (G079-G082)
        Route::post('/comms/send', [\App\Http\Controllers\Communications\OutboundMessageController::class, 'send'])->name('comms.send');
        Route::get('/comms/messages', [\App\Http\Controllers\Communications\OutboundMessageController::class, 'index'])->name('comms.messages.index');
        Route::post('/comms/campaigns', [\App\Http\Controllers\Communications\MessageCampaignController::class, 'store'])->name('comms.campaigns.store');
        Route::post('/comms/campaigns/{campaign}/start', [\App\Http\Controllers\Communications\MessageCampaignController::class, 'start'])->name('comms.campaigns.start');

        // Batch Operations
        Route::prefix('batch')->name('batch.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'index'])->name('index');
            Route::post('/leave-requests', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'batchLeaveRequests'])->name('leave-requests');
            Route::post('/attendance', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'batchMarkAttendance'])->name('attendance');
            Route::post('/payroll', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'batchGeneratePayroll'])->name('payroll');
            Route::post('/id-cards', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'batchGenerateIdCards'])->name('id-cards');
            Route::post('/export', [\App\Http\Controllers\Hms\BatchOperationsController::class, 'batchExport'])->name('export');
        });

        // ICT Department (M52 / G088)
        Route::post('/ict/assets', [\App\Http\Controllers\Hms\ItAssetController::class, 'store'])->name('ict.assets.store');
        Route::get('/ict/assets', [\App\Http\Controllers\Hms\ItAssetController::class, 'index'])->name('ict.assets.index');
        Route::post('/ict/tickets', [\App\Http\Controllers\Hms\ItTicketController::class, 'store'])->name('ict.tickets.store');
        Route::get('/ict/tickets', [\App\Http\Controllers\Hms\ItTicketController::class, 'index'])->name('ict.tickets.index');
        Route::post('/ict/tickets/{ticket}/resolve', [\App\Http\Controllers\Hms\ItTicketController::class, 'resolve'])->name('ict.tickets.resolve');
       Route::post('/ict/backups', [\App\Http\Controllers\Hms\BackupController::class, 'store'])->name('ict.backups.store')->middleware('permission:manage backups');
       Route::get('/ict/backups', [\App\Http\Controllers\Hms\BackupController::class, 'index'])->name('ict.backups.index')->middleware('permission:manage backups');
       Route::post('/ict/backups/{backup}/verify', [\App\Http\Controllers\Hms\BackupController::class, 'verify'])->name('ict.backups.verify')->middleware('permission:manage backups');
       Route::delete('/ict/backups/{backup}', [\App\Http\Controllers\Hms\BackupController::class, 'destroy'])->name('ict.backups.destroy')->middleware('permission:manage backups');
        Route::post('/ict/software-licences', [\App\Http\Controllers\Hms\SoftwareLicenceController::class, 'store'])->name('ict.software-licences.store');
        Route::get('/ict/software-licences', [\App\Http\Controllers\Hms\SoftwareLicenceController::class, 'index'])->name('ict.software-licences.index');

        // Fire, Safety & Occupational Health (M37 / G056)
        Route::prefix('ohs')->name('ohs.')->group(function () {
            Route::get('/safety-incidents', [\App\Http\Controllers\Hms\SafetyIncidentController::class, 'index'])->name('safety-incidents.index');
            Route::post('/safety-incidents', [\App\Http\Controllers\Hms\SafetyIncidentController::class, 'store'])->name('safety-incidents.store');
            Route::post('/safety-incidents/{incident}/resolve', [\App\Http\Controllers\Hms\SafetyIncidentController::class, 'resolve'])->name('safety-incidents.resolve');
            Route::get('/fire-equipment', [\App\Http\Controllers\Hms\FireEquipmentController::class, 'index'])->name('fire-equipment.index');
            Route::post('/fire-equipment', [\App\Http\Controllers\Hms\FireEquipmentController::class, 'store'])->name('fire-equipment.store');
            Route::post('/fire-inspections', [\App\Http\Controllers\Hms\FireInspectionController::class, 'store'])->name('fire-inspections.store');
            Route::post('/emergency-drills', [\App\Http\Controllers\Hms\EmergencyDrillController::class, 'store'])->name('emergency-drills.store');
            Route::get('/risk-assessments', [\App\Http\Controllers\Hms\RiskAssessmentController::class, 'index'])->name('risk-assessments.index');
            Route::post('/risk-assessments', [\App\Http\Controllers\Hms\RiskAssessmentController::class, 'store'])->name('risk-assessments.store');
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
        Route::post('/modules/{module}/toggle', [\App\Http\Controllers\Admin\ModulesController::class, 'toggle'])->name('modules.toggle');
        Route::post('/modules/{module}/enable', [\App\Http\Controllers\Admin\ModulesController::class, 'enable'])->name('modules.enable');
        Route::post('/modules/{module}/disable', [\App\Http\Controllers\Admin\ModulesController::class, 'disable'])->name('modules.disable');
        Route::post('/modules/enable-all', [\App\Http\Controllers\Admin\ModulesController::class, 'enableAll'])->name('modules.enable-all');
        
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

    // ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ Break-glass emergency access (audited) ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬
    Route::prefix('break-glass')->name('break-glass.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Hms\BreakGlassController::class, 'index'])->name('index')->middleware('permission:manage break glass events|view audit logs');
        Route::get('/create', [\App\Http\Controllers\Hms\BreakGlassController::class, 'create'])->name('create')->middleware('permission:manage break glass events|view audit logs');
        Route::post('/', [\App\Http\Controllers\Hms\BreakGlassController::class, 'store'])->name('store')->middleware('auth');
        Route::post('/{event}/approve', [\App\Http\Controllers\Hms\BreakGlassController::class, 'approve'])->name('approve')->middleware('permission:manage break glass events|view audit logs');
        Route::post('/{event}/reject', [\App\Http\Controllers\Hms\BreakGlassController::class, 'reject'])->name('reject')->middleware('permission:manage break glass events|view audit logs');
    });

    // ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ Patient 360 (permission-filtered consolidated view) ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬
    Route::get('/patients/{patient}/360', [\App\Http\Controllers\Hms\Patient360Controller::class, 'show'])
        ->middleware('permission:view patients|view test results|view prescriptions|view billing|view invoices|view payment reports')
        ->name('patients.360');

    }); // close auth middleware group

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


