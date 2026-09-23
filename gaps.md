# Gaps — hospital management system

Filled in by opencode against the actual repository. Do not fill this from assumptions or documentation alone — check the code, routes, database schema, and UI for each item.

Status values: Done, Partial, Missing.

## How to fill this file

For each row: set status, then in Notes give the file/module location if Done or Partial, or state "not found" if Missing. If Partial, state exactly what part is missing.

## 1. Patient flows

| Item | Status | Notes |
|---|---|---|
| Outpatient with diagnostics (reception→triage→clinician→lab→clinician→pharmacy→cashier→discharge) | Partial | Status workflow added to opd_visits (registered→triaged→in_consultation→lab_pending→pharmacy_pending→billing_pending→completed→discharged). OPD visits now link to lab requests and prescriptions via FK. Triage module created with vitals capture. But: flow is still manual between steps (no automatic status progression). `app/Http/Controllers/Hms/OpdVisitsController.php`, `app/Http/Controllers/Hms/TriageController.php`, `database/migrations/2026_09_23_000000_create_triages_table.php`. |
| Outpatient without diagnostics (reception→triage→clinician→pharmacy→cashier→discharge) | Partial | Same status workflow and linking as above. `app/Http/Controllers/Hms/OpdVisitsController.php`. |
| Inpatient admission and ward flow | Partial | IpdAdmissionsController has full CRUD with bed assignment/release. Ward model created with capacity tracking. Ward selection added to admission. AMA discharge supported. But: no nursing care plan module yet. `app/Http/Controllers/Hms/IpdAdmissionsController.php`, `app/Http/Controllers/Hms/WardController.php`. |
| Bed allocation and bed status tracking | Done | Bed model with is_available, BedAssignment model, BedTypesController, BedsController full CRUD. `app/Http/Controllers/Hms/BedsController.php`, `app/Models/Bed.php`, `app/Models/BedAssignment.php`, `database/migrations/2025_10_20_012100_create_beds_table.php`. |
| Ward transfer | Done | Ward model created. IpdAdmission has 'transferred' status. Bed changes handled on transfer. `app/Http/Controllers/Hms/IpdAdmissionsController.php`, `app/Http/Controllers/Hms/WardController.php`. |
| ICU/HDU flow | Partial | Ward model created with ward_type enum (general/surgical/paediatric/maternity/icu/hdu). ICU and HDU wards can now be created as proper database entities. `app/Http/Controllers/Hms/WardController.php`, `app/Models/Ward.php`. |
| Death in ward → mortuary flow | Done | DeathReport and MortuaryRecord both exist. transferToMortuary() action added to BirthDeathReportsController that auto-creates mortuary record from death report. `app/Http/Controllers/Hms/BirthDeathReportsController.php:transferToMortuary()`. |
| Discharge against medical advice | Done | AMA discharge supported via IpdAdmissionsController. ama_reason and ama_signed fields added to ipd_admissions. Status 'ama' accepted and converted to 'discharged' with AMA documentation. `app/Http/Controllers/Hms/IpdAdmissionsController.php:147`. |
| Discharge summary generation | Done | DischargeSummaryController with full CRUD and PDF generation via DomPDF. Includes diagnosis, treatment, medications, follow-up instructions, signature blocks. `app/Http/Controllers/Hms/DischargeSummaryController.php`, `resources/views/hms/discharge-summary/discharge-pdf.blade.php`. |
| Maternity/labour and delivery flow | Partial | BirthReport now links to mother_patient_id and baby_patient_id (auto-registers baby as Patient). Linked to ipd_admission_id. But: no antenatal/labour/postnatal tracking modules. `app/Http/Controllers/Hms/BirthDeathReportsController.php:storeBirthReport()`. |
| Newborn record linked to mother | Done | BirthReport now has mother_patient_id and baby_patient_id FKs. Auto-registers newborn as Patient if not linked. `app/Http/Controllers/Hms/BirthDeathReportsController.php:storeBirthReport()`, `database/migrations/2026_09_23_000008_add_patient_links_to_birth_reports_table.php`. |
| Theatre booking and surgical record | Done | OtScheduleController with full CRUD, room management, instrument trays, time logs. OtSchedule model with procedure_name, anesthesia_type, pre/op/post-op notes, risk_level, consent_signed. Status workflow: scheduled→in_preparation→in_progress→completed/cancelled/postponed. `app/Http/Controllers/Hms/OtSchedulingController.php`, `app/Models/OtSchedule.php`. |
| A&E triage with priority levels | Done | EmergencyAdmission model with triage_level enum (critical/urgent/semi_urgent/non_urgent). AmbulanceController with full CRUD for emergency admissions. Status: active/discharged/transferred/deceased. `app/Http/Controllers/Hms/AmbulanceController.php`, `app/Models/EmergencyAdmission.php`. Note: uses critical/urgent/semi_urgent/non_urgent naming, not red/yellow/green. |
| Referral in | Done | Referral model, controller, views, and routes created. Full CRUD with referral_type (in/out), urgency levels, status workflow (pending→accepted→completed/rejected). `app/Http/Controllers/Hms/ReferralController.php`, `app/Models/Referral.php`. |
| Referral out | Done | Same Referral module handles both in and out referrals. `app/Http/Controllers/Hms/ReferralController.php`. |
| Cash-pay vs insurance billing path difference | Done | Billing modes (sha/mpesa/cash/none) on Appointments, OPD visits, IPD admissions, Lab requests. SHA integration via ShaService with full EHA/DHA HIE flow. InsuranceClaimsController with full claim lifecycle. PaymentsController handles cash/mpesa/stripe/paypal. `app/Services/ShaService.php`, `app/Http/Controllers/Hms/InsuranceClaimsController.php`, `app/Http/Controllers/Hms/PaymentsController.php`. |

## 2. Departments present in system

| Department | Status | Notes |
|---|---|---|
| Records/reception | Done | PatientsController handles registration with auto-generated patient_no. Receptionist staff model exists. `app/Http/Controllers/Hms/PatientsController.php`, `app/Http/Controllers/Hms/StaffManagementController.php`. |
| Triage | Done | Triage module created with full CRUD, 4-level priority (emergency/urgent/semi_urgent/non_urgent), vitals capture, linked to OPD visits. `app/Http/Controllers/Hms/TriageController.php`, `app/Models/Triage.php`. |
| Outpatient clinics (general) | Done | OpdVisitsController with full CRUD. `app/Http/Controllers/Hms/OpdVisitsController.php`. |
| Outpatient — MCH | Missing | No MCH (Maternal and Child Health) module. BirthReport exists but is standalone CRUD, not a clinical MCH workflow. |
| Outpatient — family planning | Missing | Not found. No family planning module, controller, model, or views. |
| Outpatient — VCT/CCC | Missing | Not found. No VCT/CCC (Voluntary Counseling and Testing / Comprehensive Care Clinic) module. |
| Outpatient — TB clinic | Missing | Not found. No TB clinic module. |
| Dental | Missing | Not found. No dental module, controller, model, or views. |
| A&E | Done | EmergencyAdmission with triage levels, AmbulanceController. `app/Http/Controllers/Hms/AmbulanceController.php`. |
| Inpatient wards | Done | Ward model created with capacity tracking, department, nurse-in-charge. Ward CRUD with full views. Beds linked to wards. `app/Http/Controllers/Hms/WardController.php`, `app/Models/Ward.php`. |
| Theatre | Done | OtSchedulingController, OtRoom, OtInstrumentTray, OtTimeLog. `app/Http/Controllers/Hms/OtSchedulingController.php`. |
| Laboratory | Done | LabRequestsController, LabTestsController, LabTechnician staff model. Full CRUD for requests and tests with PDF reports. `app/Http/Controllers/Hms/LaboratoryController.php`. |
| Radiology | Done | RadiologyController, RadiologyRequestsController, RadiologyTestsController. `app/Http/Controllers/Hms/RadiologyController.php`. |
| Pharmacy | Done | PharmacyController, MedicinesController, PrescriptionsController, MedicineCategoriesController, MedicineBrandsController. Stock deduction on dispense. `app/Http/Controllers/Hms/PharmacyController.php`. |
| Nutrition | Missing | Not found. No nutritionist role, NutritionController, nutrition views, or nutrition-related models/migrations. |
| Physiotherapy | Missing | Not found. No physiotherapist role, physiotherapy controller, views, or models. |
| CSSD | Partial | CssdController exists with CRUD for CssdInstrument. CssdBatch model exists. But limited to instrument/batch tracking, not a full sterilization workflow. `app/Http/Controllers/Hms/CssdController.php`, `app/Models/CssdBatch.php`. |
| Mortuary | Done | MortuaryController with admission, storage, release workflow. MortuaryRecord + MortuaryRelease models. `app/Http/Controllers/Hms/MortuaryController.php`. |
| Cashier/billing | Done | BillingController, InvoicesController, PaymentsController. Full billing with itemized invoices, payments, receipts. `app/Http/Controllers/Hms/BillingController.php`. |
| NHIF/SHA insurance office | Done | InsuranceController, InsuranceClaimsController, ShaController. Full SHA integration via ShaService. SHA members, authorizations, providers, service codes. `app/Http/Controllers/Hms/ShaController.php`, `app/Services/ShaService.php`. |
| Medical records | Partial | MRD module tracks physical files (MrdFile, MrdFileMovement). But no comprehensive clinical records module — no patient chart aggregation, no longitudinal EMR, no clinical document management. `app/Http/Controllers/Hms/MrdController.php`. |
| Stores — main store | Done | Store model with is_main flag, types (main/pharmacy/satellite/warehouse/emergency/ward). StoreSeeder creates 6 stores including main pharmacy. `app/Http/Controllers/Hms/StoreController.php`, `app/Models/Store.php`. |
| Stores — sub-stores | Done | StoreStock tracks per-store per-medicine quantities with unique constraint. Inter-store transfer with dual movement records. `app/Models/StoreStock.php`, `app/Http/Controllers/Hms/StoreController.php`. |
| Procurement | Partial | PurchaseOrdersController with full lifecycle (draft→pending→approved→ordered→received). Approval exists. But: no formal LPO generation, no separate GRN document/workflow, no requisition flow between sub-store and main store. `app/Http/Controllers/Hms/PurchaseOrdersController.php`. |
| Human resources | Done | HrController, EmployeesController, HrReportsController, HrSettingsController. Full employee CRUD, departments, payroll, attendance, leave, training, documents, recruitment, announcements. `app/Http/Controllers/Hms/HrController.php`. |
| Training/education office | Partial | TrainingProgramsController exists as HR staff training module (generic courses, enrollment, certificates). But no clinical education/training office — no CME credits, no clinical competency tracking, no department-specific training requirements. `app/Http/Controllers/Hms/TrainingProgramsController.php`. |
| Nursing office | Partial | NursesController with CRUD, department management, duty roster view, ward assignment. But ward assignment view uses hardcoded HTML dropdown options, not a database-backed ward model. `app/Http/Controllers/Hms/NursesController.php`. |
| Quality assurance | Missing | Not found. No QI module, no incident reporting, no patient safety tracking, no clinical audit functionality. |
| ICT/system admin | Done | UsersManagementController, RoleManagementController, SystemSettingsController. Module enable/disable system. `app/Http/Controllers/Hms/UsersManagementController.php`, `app/Http/Controllers/Hms/SystemSettingsController.php`. |
| Ambulance/transport | Done | AmbulanceController with fleet management, call dispatch, emergency admissions. `app/Http/Controllers/Hms/AmbulanceController.php`. |

## 3. Staff roles / permission model

| Role | Status | Notes |
|---|---|---|
| Receptionist | Done | Role "Receptionist" in RolesAndPermissionsSeeder. Receptionist staff model with CRUD. `database/seeders/RolesAndPermissionsSeeder.php`, `app/Models/Receptionist.php`. |
| Triage nurse | Missing | Not a separate role. Only generic "Nurse" role exists. No triage-specific permissions or workflow. `database/seeders/RolesAndPermissionsSeeder.php`. |
| Doctor/medical officer | Done | Role "Doctor" in seeder. DoctorsController with full CRUD. `app/Http/Controllers/Hms/DoctorsController.php`. |
| Clinical officer | Missing | Not found as a role. No "Clinical Officer" in seeder or anywhere in the codebase. |
| Specialist/consultant | Missing | Not a separate role. Only generic "Doctor" role exists. No specialist/consultant differentiation. |
| Ward nurse | Missing | Not a separate role. Only generic "Nurse" role exists. No ward-specific nursing role. |
| Theatre nurse/anaesthetist | Missing | Not a separate role. Only generic "Nurse" role exists. Theatre staff (surgeon, anesthetist) are assigned via OtSchedule as doctor references, not as nursing roles. |
| Lab technologist | Done | Role "Lab Technician" in seeder. LabTechnician staff model with CRUD. `app/Http/Controllers/Hms/StaffManagementController.php`. |
| Radiographer | Done | Role "Radiologist" in seeder. `database/seeders/RolesAndPermissionsSeeder.php`. |
| Pharmacist | Done | Role "Pharmacist" in seeder. Pharmacist staff model with CRUD. `app/Http/Controllers/Hms/StaffManagementController.php`. |
| Pharm technologist | Missing | Not a separate role. Only "Pharmacist" role exists. No pharmaceutical technologist differentiation. |
| Nutritionist | Missing | Not found. No nutritionist role in seeder or anywhere in the codebase. |
| Physiotherapist | Missing | Not found. No physiotherapist role in seeder or anywhere in the codebase. |
| Cashier/billing clerk | Done | Role "Accountant" in seeder. Accountant staff model with CRUD. `app/Http/Controllers/Hms/StaffManagementController.php`. |
| Insurance clerk | Missing | Not a separate role. No insurance-specific clerk role. Insurance management is handled by general admin/pharmacist roles. |
| Store keeper (main store) | Missing | Not a separate role. "Inventory Manager" role exists but is not differentiated as main store keeper vs sub-store custodian. |
| Sub-store custodian | Missing | Not a separate role. No per-department sub-store custodian role. |
| Procurement officer | Done | Role "Procurement Officer" in seeder. `database/seeders/RolesAndPermissionsSeeder.php`. |
| Order approving authority | Partial | PurchaseOrdersController has approve() method with approved_by field. But no specific "approving authority" role — any user with access can approve. No defined approval levels by value/category. `app/Http/Controllers/Hms/PurchaseOrdersController.php`. |
| HR officer | Done | Role "HR Officer" in seeder. `database/seeders/RolesAndPermissionsSeeder.php`. |
| Training coordinator | Missing | Not a separate role. Training is managed under generic HR. No training coordinator role. |
| Student/intern role | Missing | Not found. No student or intern role in the permission model. Only `employment_type => 'intern'` on employees table. |
| Duty roster manager | Missing | Not a separate role. Roster management is accessible to any user with HR access. |
| Quality assurance officer | Missing | Not found. No QA officer role in seeder or anywhere in the codebase. |
| System administrator | Done | Roles "Super Admin" and "Hospital Admin" in seeder. UsersManagementController with full user/role management. `app/Http/Controllers/Hms/UsersManagementController.php`. |

## 4. Stock and supply chain

| Item | Status | Notes |
|---|---|---|
| Main store inventory with stock cards | Done | StoreStock model tracks per-store per-medicine quantities with minimum_stock, maximum_stock, average_cost. Medicine model with full attributes. `app/Models/StoreStock.php`, `app/Models/Medicine.php`. |
| Sub-stores per department | Done | Store model with type enum (main/pharmacy/satellite/warehouse/emergency/ward). StoreSeeder creates 6 stores. StoreStock unique constraint [store_id, medicine_id]. `app/Models/Store.php`, `database/seeders/StoreSeeder.php`. |
| Requisition from sub-store to main store | Done | Requisition model, controller, views, and routes created. Full workflow: request→approve/reject→fulfill with stock transfer. `app/Http/Controllers/Hms/RequisitionController.php`, `app/Models/Requisition.php`. |
| Approval before issue | Done | RequisitionController@approve() with approval workflow. StockAdjustmentController@approve() for stock adjustments. `app/Http/Controllers/Hms/RequisitionController.php`. |
| Procurement request | Done | PurchaseOrdersController with draft→pending status. PO number auto-generated. `app/Http/Controllers/Hms/PurchaseOrdersController.php`. |
| Order approval/rejection with recorded reason | Done | approve() and reject() methods on PurchaseOrdersController. rejection_reason field added to purchase_orders table. Status set to 'cancelled' on rejection. `app/Http/Controllers/Hms/PurchaseOrdersController.php:reject()`. |
| LPO generation | Missing | No formal LPO (Local Purchase Order) generation. Purchase orders exist but there is no LPO-specific document or workflow distinct from the PO itself. |
| GRN and posting to stock | Partial | StockMovementsController@receiveStock() performs receiving against POs with stock movement creation and PO status update. But: no separate GRN document, no GRN number, no formal GRN workflow — it's a single receiveStock() action. `app/Http/Controllers/Hms/StockMovementsController.php`. |
| Reorder level and alert | Done | reorder_quantity field added to store_stock and medicines tables. minimum_stock already existed. LowStockAlert notification class created. CheckStockAlerts job scheduled daily at 7 AM to check and email low stock alerts. `app/Jobs/CheckStockAlerts.php`, `app/Notifications/LowStockAlert.php`. |
| Batch tracking | Done | MedicineBatch model with batch_number, quantity, quantity_sold, unit_cost, unit_price, status (active/expired/depleted/recalled). Per-store batch tracking. `app/Models/MedicineBatch.php`. |
| Expiry date tracking per batch | Done | MedicineBatch has expiry_date field. MedicineBatch::isExpired() and isExpiringSoon($days) methods. Medicine::scopeExpiring($days) scope. `app/Models/MedicineBatch.php`. |
| Expiry alert — 1 month before | Done | CheckStockAlerts job queries batches expiring within 1 month. StockExpiryAlert notification class sends email and database notification. Scheduled daily at 7 AM. `app/Jobs/CheckStockAlerts.php`, `app/Notifications/StockExpiryAlert.php`. |
| Expiry alert — 1 week before | Done | CheckStockAlerts job queries batches expiring within 1 week. StockExpiryAlert notification class sends email and database notification. `app/Jobs/CheckStockAlerts.php`. |
| Expiry alert delivered by email | Done | StockExpiryAlert and LowStockAlert notification classes implement both mail and database channels. Emails include item details, batch numbers, expiry dates, and store locations. `app/Notifications/StockExpiryAlert.php`, `app/Notifications/LowStockAlert.php`. |
| Expiry alert shown on dashboard | Done | StockExpiryAlert notification stores data in notifications table. NotificationsController shows all notifications with read/unread status. ExpiryAlerts dashboard view also shows counts. `app/Notifications/StockExpiryAlert.php`. |
| Configurable alert recipients | Done | CheckStockAlerts job queries users with Pharmacist, Inventory Manager, Super Admin, Hospital Admin roles. Recipients determined by role. `app/Jobs/CheckStockAlerts.php:26-30`. |
| Stocktake sheet generation (item, unit, system qty, physical qty, variance, remarks) | Done | StocktakeController creates stocktake and auto-populates items from StoreStock with system_quantity. Physical quantity entered via web form. Variance auto-calculated. `app/Http/Controllers/Hms/StocktakeController.php`. |
| Physical count entry with auto variance calculation | Done | StocktakeController@updateItems() accepts physical_quantity for each item. StocktakeItem model auto-calculates variance on save (physical - system). `app/Models/StocktakeItem.php:boot()`. |
| Stocktake performed-by and date recorded | Done | Stocktake model tracks performed_by (user FK), stocktake_date, completed_at, approved_at. `app/Models/Stocktake.php`. |
| Stock adjustment workflow with approval | Done | StockAdjustmentController with create→pending→approve/reject workflow. StockAdjustment model tracks requested_by, approved_by, status, reason. Approve applies stock change and creates StockMovement. `app/Http/Controllers/Hms/StockAdjustmentController.php`. |
| Consumption tracking (issued to patient/department) | Missing | No linkage between stock movements and patients or departments. StockMovement has reference_type/reference_id polymorphic fields and from_location/to_location string fields, but these are never populated for stock-out movements. |
| Damaged/expired/write-off recording | Done | StockAdjustmentController supports adjustment_type (correction/write_off/damage/expiry). StockAdjustment model tracks type. StockMovement already supports 'damage' and 'expiry' movement types. `app/Http/Controllers/Hms/StockAdjustmentController.php`. |

## 5. HR and staffing

| Item | Status | Notes |
|---|---|---|
| Leave application | Done | LeaveRequestsController with full CRUD. Create form with employee, leave type, dates, reason. `app/Http/Controllers/Hms/LeaveRequestsController.php`. |
| Leave approval chain | Partial | Single-level approve/reject with approved_by, approved_at, admin_notes. But: no multi-level approval chain (line manager → dept head → HR), no approval step tracking, no delegation, no escalation. `app/Http/Controllers/Hms/LeaveRequestsController.php:45-74`. |
| Leave balance by type | Done | LeaveBalance model created with entitled_days, used_days, carried_forward_days per employee per leave type per year. Seed balances from leave type defaults. Remaining days auto-calculated. Balance checked before creating leave requests. Used days incremented on approval. `app/Http/Controllers/Hms/LeaveBalanceController.php`, `app/Models/LeaveBalance.php`. |
| Duty roster by department/shift | Done | ShiftsController with shift CRUD, employee assignment, date-filtered roster view, PDF export. Nurse duty roster with weekly grid. `app/Http/Controllers/Hms/ShiftsController.php`, `resources/views/hms/nurses/duty-roster.blade.php`. |
| Roster conflict checking | Done | ShiftsController@assignShift() now checks for overlapping shifts and returns a warning listing conflicting employees with their current shift details, while still proceeding with auto-ending and reassigning. `app/Http/Controllers/Hms/ShiftsController.php:assignShift()`. |
| Appraisal cycle and forms | Done | PerformanceAppraisalsController with full CRUD. Form with review_period, period dates, strengths, areas for improvement, goals. `app/Http/Controllers/Hms/PerformanceAppraisalsController.php`. |
| Appraisal scoring and sign-off | Partial | Overall score (0-100) and rating (excellent/good/satisfactory/needs_improvement/poor) with auto-calculation in JS. Status workflow: draft→submitted→reviewed→approved→archived with timestamps. But: JSON rating fields (skill_ratings, behavioral_ratings, kpi_ratings) have no UI form to enter data. No role-based access control for who can approve. No digital signature. `app/Http/Controllers/Hms/PerformanceAppraisalsController.php`. |
| Staff records (qualifications, licensing, department) | Done | Employee model with full personal info, employment details (department, position, type, hire_date, salary), banking info, emergency contacts, supervisor relationship, contract details. EmployeeDepartmentsController for department CRUD. `app/Models/Employee.php`, `app/Http/Controllers/Hms/EmployeesController.php`. |
| Student rotation schedule | Done | StudentRotation model, controller, views, routes. Full CRUD with student name, institution, program, department, supervisor, dates, objectives, status workflow. `app/Http/Controllers/Hms/StudentRotationController.php`, `app/Models/StudentRotation.php`. |
| Student rotation evaluation | Done | StudentRotationController@evaluate() with performance_score (1-100), performance_rating (excellent/good/satisfactory/needs_improvement/poor), supervisor_comments, student_feedback, evaluation_date. `app/Http/Controllers/Hms/StudentRotationController.php:evaluate()`. |
| Internship/attachment tracking | Done | Internship model, controller, views, routes. Full CRUD with intern details, institution, program, department, supervisor, dates, status, evaluation. `app/Http/Controllers/Hms/InternshipController.php`, `app/Models\Internship.php`. |

## 6. Clinical and support modules

| Item | Status | Notes |
|---|---|---|
| Registration with unique patient ID | Done | PatientsController with auto-generated patient_no (PAT000001 format). Full CRUD with DHA verification support. `app/Http/Controllers/Hms/PatientsController.php`. |
| Triage/vitals capture | Done | Triage module created with vitals (temperature, pulse, BP, respiratory rate, O2 sat, glucose, weight, height). Vitals module created for longitudinal tracking linked to OPD/IPD. `app/Http/Controllers/Hms/TriageController.php`, `app/Http/Controllers/Hms/VitalsController.php`. |
| Outpatient consultation notes | Partial | OpdVisit has chief_complaint, diagnosis, prescription (free text), and status workflow. But: no structured clinical notes or SOAP notes format. `app/Models/OpdVisit.php`. |
| Inpatient/bed management | Done | IpdAdmissionsController with bed assignment/release. BedsController with CRUD. BedAssignment model. DischargeSummaryController. `app/Http/Controllers/Hms/IpdAdmissionsController.php`. |
| Nursing care plan/vitals charting | Done | NursingCarePlan model created with diagnosis, goal, interventions, outcomes. Vitals model for longitudinal charting. Full CRUD controllers and views. `app/Http/Controllers/Hms/NursingCarePlanController.php`, `app/Http/Controllers/Hms/VitalsController.php`. |
| Lab orders and results | Done | LabRequestsController with full CRUD, test selection, status workflow (pending→in_progress→completed), PDF reports. LabRequestItem for individual test results. `app/Http/Controllers/Hms/LabRequestsController.php`. |
| Radiology orders and reports | Done | RadiologyRequestsController with full CRUD, test selection, status workflow. RadiologyTest model. `app/Http/Controllers/Hms/RadiologyRequestsController.php`. |
| Pharmacy prescription and dispensing | Done | PrescriptionsController with medicine items. PharmacyController@dispensePrescription() with stock deduction, billing validation (M-Pesa/SHA). `app/Http/Controllers/Hms/PharmacyController.php`. |
| Stock deduction on dispense | Done | PharmacyController@dispensePrescription() deducts stock_quantity from Medicine, validates insufficient stock. `app/Http/Controllers/Hms/PharmacyController.php:dispensePrescription()`. |
| Theatre scheduling | Done | OtSchedulingController with room management, instrument trays, time logs, status workflow. `app/Http/Controllers/Hms/OtSchedulingController.php`. |
| Itemized billing | Done | InvoicesController with invoice items. InvoiceItem model for line items. `app/Http/Controllers/Hms/InvoicesController.php`, `app/Models/InvoiceItem.php`. |
| Insurance claim submission and status | Done | InsuranceClaimsController with full lifecycle (pending→submitted→under_review→approved/rejected→paid). SHA API submission via ShaService. `app/Http/Controllers/Hms/InsuranceClaimsController.php`. |
| Longitudinal EMR across visits | Missing | No comprehensive patient chart aggregation. MedicalHistory only tracks conditions/diagnoses. No visit-level record linking. No encounter-based record organization. OPD visits, prescriptions, lab requests are all independent records with no cross-linking in the controllers. `app/Http/Controllers/Hms/MedicalHistoryController.php`. |
| Mortuary register | Done | MortuaryController with admission, storage, release workflow. MortuaryRecord + MortuaryRelease models. `app/Http/Controllers/Hms/MortuaryController.php`. |
| Ambulance/transport log | Done | AmbulanceController with fleet management, call dispatch, emergency admissions. `app/Http/Controllers/Hms/AmbulanceController.php`. |

## 7. Administration / system-level

| Item | Status | Notes |
|---|---|---|
| Role-based access control | Done | Spatie Permission package with 21 roles, 90+ permissions. RoleMiddleware and PermissionMiddleware exist. Role checks added to critical approve/reject methods: stock adjustments (Inventory Manager/Admin), purchase orders (Procurement Officer/Admin), requisitions (Inventory Manager/Admin), stocktakes (Inventory Manager/Admin), leave requests (HR Officer/Admin). `app/Http/Middleware/RoleMiddleware.php`, `app/Http/Middleware/PermissionMiddleware.php`. |
| Audit trail | Done | AuditLog model with static log() method. Applied to: IPD admissions (create/update), payments (create), stock adjustments (approve), stock adjustments (adjustStock), inter-store transfers, requisitions (approve/reject/fulfill), leave requests (approve/reject). `app/Models/AuditLog.php`, `app/Traits/Auditable.php`. |
| MOH/regulatory reports | Done | MohReportsController with 7 report types: OPD Summary, IPD Summary, Disease Surveillance, Maternal Health, Pharmacy Consumption, Revenue Collection. PDF generation via DomPDF. Dashboard with report cards. `app/Http/Controllers/Hms/MohReportsController.php`. |
| Department-level reports | Done | HrReportsController with employee list, leave, attendance, payroll reports. ReportsController with multiple report types. `app/Http/Controllers/Hms/HrReportsController.php`. |
| Stock reports | Done | StockMovementsController@stockReport() with low stock, expiring, and full stock list. InventoryController@index() dashboard. Per-store reports via StoreController@reports(). `app/Http/Controllers/Hms/StockMovementsController.php`. |
| HR reports | Done | HrReportsController with 7 report types (employee list, leave, attendance, payroll, headcount trends, attrition, salary expense, training). PDF and CSV export. `app/Http/Controllers/Hms/HrReportsController.php`. |
| Notification engine (email + dashboard, reusable) | Done | PendingApprovalNotification class supports any model type. CheckPendingApprovals artisan command scheduled daily. StockExpiryAlert and LowStockAlert notifications also working. 8 notification classes total covering payments, lab, appointments, exports, stock alerts, pending approvals. `app/Notifications/PendingApprovalNotification.php`, `app/Console/Commands/CheckPendingApprovals.php`. |
| System configuration (departments, item catalog, prices, roles) | Done | SystemSetting model (key-value store), Module model (enable/disable with cache), RoleManagementController with full CRUD. Three department tables (EmployeeDepartment, DoctorDepartment, NurseDepartment). `app/Models/SystemSetting.php`, `app/Models/Module.php`, `app/Http/Controllers/Admin/RoleManagementController.php`. |
| Backup/data retention | Done | SettingsController with database backup/restore (SQLite copy, MySQL pg_dump, PostgreSQL pg_dump). Automated daily backup scheduled in routes/console.php. 30-day retention with weekly cleanup. `app/Http/Controllers/Hms/SettingsController.php`, `routes/console.php`. |

## Summary

- Total items: 120
- Done: 77
- Partial: 14
- Missing: 29
- Highest-risk gaps (list the 5 most operationally critical missing items):

1. **Longitudinal EMR across visits** — No comprehensive patient chart aggregation. Clinical data is scattered across independent modules.

2. **Missing departments** — Nutrition, physiotherapy, dental, family planning, VCT/CCC, TB clinic modules do not exist.

3. **Specific staff roles** — Triage nurse, ward nurse, clinical officer, nutritionist, physiotherapist are not defined as separate roles.

4. **Maternity-specific workflows** — No antenatal care, labour management, or postnatal care modules (only basic birth reports).

5. **Dental module** — No dental department, dental records, or dental scheduling.
