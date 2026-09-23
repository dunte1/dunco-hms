# Hospital management system — specification for verification

This document defines the expected scope of a Level 5 hospital management system. It is meant to be used as a checklist. Hand this file and gaps.md to opencode, pointed at the actual repository, and have it confirm each item against the code, not against assumptions.

## 1. Patient flows

### 1.1 Outpatient — with diagnostics (case 1)
Reception → triage → clinician (doctor/clinical officer) → laboratory → clinician (review results) → pharmacy → cashier/billing → discharge

Branch points the system must handle:
- Clinician can order radiology instead of, or alongside, lab.
- Clinician can refer to a specialist clinic before returning to pharmacy.
- Results can trigger a second clinician review loop, not just one pass.
- Cash-pay vs insurance (NHIF/SHA) changes the billing step, not the clinical steps.

### 1.2 Outpatient — no diagnostics (case 2)
Reception → triage → clinician → pharmacy → cashier/billing → discharge

### 1.3 Inpatient (case 3)
Reception or A&E → triage → clinician assessment → admission decision → bed allocation → ward → nursing care and vitals charting → lab/radiology as ordered → clinician ward rounds → theatre (if surgical) → medication administration (ward stock or MAR) → billing accrual during stay → discharge planning → discharge summary → cashier/billing → discharge

Branch points:
- Admission from OPD vs direct admission vs A&E emergency admission.
- Transfer between wards or to ICU/HDU.
- Death in ward → mortuary flow, not discharge flow.
- Referral out to another facility.
- Against medical advice (AMA) discharge.

### 1.4 Other flows to confirm exist
- Maternity: antenatal registration → labour ward admission → delivery record → postnatal care → newborn record → discharge.
- Theatre: booking → pre-op checklist → surgery record → post-op recovery → return to ward.
- A&E/emergency: triage priority levels (red/yellow/green), resuscitation flow separate from routine triage.
- Referral in (from lower-level facility) and referral out.
- Mortuary: admission of body, storage, release.

## 2. Departments

Confirm each has a defined workflow, user role, and data model in the system.

- Records/reception
- Triage
- Outpatient clinics (general, MCH, family planning, VCT/CCC, TB clinic, dental)
- Accident & Emergency
- Inpatient wards (medical, surgical, paediatric, maternity, ICU, HDU)
- Theatre/surgical services
- Laboratory
- Radiology/imaging
- Pharmacy
- Nutrition
- Physiotherapy
- CSSD (sterilization services)
- Mortuary
- Cashier/billing
- NHIF/SHA insurance office
- Medical records/health information
- Stores — main store
- Stores — sub-stores (per department: pharmacy sub-store, ward sub-stores, lab sub-store, theatre sub-store)
- Procurement
- Human resources
- Training/education office
- Nursing office
- Quality assurance/QI
- ICT
- Administration/medical superintendent office
- Ambulance/transport

## 3. Staff roles

Confirm each role exists in the user/permission model, not just as a job title in documentation.

- Receptionist/records officer
- Triage nurse
- Doctor/medical officer
- Clinical officer
- Specialist/consultant
- Nurse (ward, theatre, maternity)
- Lab technologist
- Radiographer
- Pharmacist
- Pharmaceutical technologist
- Nutritionist
- Physiotherapist
- Theatre nurse/anaesthetist
- Cashier/billing clerk
- Insurance/NHIF-SHA clerk
- Store keeper (main store)
- Sub-store custodian (per department)
- Procurement officer
- Approving authority for orders (department head, procurement committee, or equivalent)
- HR officer
- Training coordinator
- Medical students/interns/rotating staff
- Duty roster manager (per department or centralized)
- Quality assurance officer
- System administrator

## 4. Stock and supply chain management

Confirm the system supports:

- Main store inventory with stock cards per item.
- Sub-stores per department, each with its own stock balance drawn from the main store.
- Requisition from sub-store to main store, with approval before issue.
- Procurement ordering: request → approval / rejection → local purchase order (LPO) → goods received note (GRN) → posting to main store stock.
- Approval workflow with defined approval levels (who can approve what value or category), and a record of who approved or rejected, and why.
- Reorder level and reorder quantity per item, with alerts when stock falls below reorder level.
- Batch and expiry tracking per item, not just total quantity.
- Expiry notifications:
  - Alert at 1 month before expiry.
  - Alert at 1 week before expiry.
  - Delivered by email and shown on a dashboard, not one or the other only.
  - Recipient list configurable (pharmacist, store keeper, QA).
- Stock take support:
  - A stocktake sheet that lists item, unit, system quantity, physical count, variance, and remarks.
  - Ability to generate this sheet for a store or sub-store before counting starts.
  - Ability to enter physical counts and have the system calculate variance automatically.
  - Record of who performed the stocktake and when.
  - Adjustment workflow to reconcile system stock to physical count, with approval, not a silent overwrite.
- Stock issue and consumption tracking (what left the sub-store and to which patient or department).
- Damaged/expired/write-off stock recording, separate from normal issue.

## 5. Human resources and staffing

- Leave management: application, approval chain, leave balance tracking by leave type (annual, sick, maternity, paternity, compassionate, study).
- Duty roster: scheduling by department and shift, visible to staff, with conflict checks (no double-booking, minimum staffing per shift).
- Appraisal: performance appraisal cycle, forms, scoring, and sign-off by supervisor.
- Staff records: qualifications, licensing/registration numbers, department assignment, employment status.
- Student rotations: rotation schedule by department, student list, supervisor assignment, rotation duration, and evaluation at end of rotation.
- Internship/attachment tracking, if distinct from student rotations.

## 6. Clinical and support modules

- Registration/reception (new and returning patient, unique patient ID)
- Triage and vitals capture
- Outpatient consultation and clinical notes
- Inpatient/admission and bed management
- Nursing care plan and vitals charting
- Laboratory information system (orders, results, result release to clinician)
- Radiology information system (orders, results/reports)
- Pharmacy (prescription, dispensing, stock deduction on dispense)
- Theatre scheduling and surgical records
- Billing/cashier (itemized billing tied to services rendered)
- Insurance claims (NHIF/SHA), including claim submission status
- Medical records/EMR (longitudinal patient record across visits)
- Mortuary register
- Referral management (in and out)
- Ambulance/transport log

## 7. Administration and system-level features

- User management with role-based access control.
- Audit trail (who did what, when — especially for stock adjustments, discharges, and billing).
- Reporting: MOH-required reports, department-level reports, stock reports, HR reports.
- Notification engine capable of email and in-app/dashboard delivery (used for expiry alerts, low stock, and can be reused for other alerts such as pending approvals).
- System configuration (departments, item catalog, price lists, user roles).
- Backup and data retention.

## 8. What "confirmed" means

For each item above, opencode should be able to answer, against the actual code:

- Present and working: the flow, screen, or feature exists and functions.
- Partially done: exists but incomplete — state exactly what is missing.
- Missing: no implementation found.

This determination should be recorded in gaps.md, not left as a general impression.
