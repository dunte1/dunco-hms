# Clinical Workflow Matrix

Generated: 2026-10-01
Legend: E=Existing, P=Partial, M=Missing, B=Blocked external

---

## Core Patient Journey

Registration -> Appointment/Queue -> Triage -> Consultation -> Diagnosis -> Orders -> Lab/Radiology/Pharmacy -> Billing -> Admission/Discharge

| Step | Status | Key files | Permissions |
|---|---|---|---|
| Registration | E | PatientsController, Patient model | view/add/edit patients |
| MRN generation | E | PatientObserver patient_no | n/a |
| Duplicate detection / merge | E | patients.merge route | merge patients |
| Appointments | E | AppointmentsController | create/manage appointments |
| Queue / token | E | QueueManagementController | manage queue |
| Triage | E | TriageController, VitalsController | manage triage records, manage patient vitals |
| Consultation / clinical notes | E | Consultation controllers | manage patient notes |
| Diagnosis | E | Diagnosis controllers | manage patient diagnoses |
| Lab orders/results | E | LabRequestsController, LabVerificationController | add test requests, verify lab results |
| Radiology | E | RadiologyRequestsController, ImagingReportController | manage radiology worklist, approve radiology reports |
| Pharmacy / prescriptions | E | PrescriptionsController, DispensationController | create prescriptions, dispense medicines |
| Billing | E | BillingController, InvoicesController | create invoices, add payments |
| Admission | E | IpdAdmissionsController | admit patients |
| Discharge | E | Discharge controllers, sign discharge summaries | manage discharges, sign discharge summaries |

Expected workflow label: Registration -> Queue -> Triage -> Consultation — IMPLEMENTED (module exists).

---

## Specialized Clinical Modules

| Module | Status | Notes |
|---|---|---|
| Emergency / Casualty | E | EmergencyAdmission, resuscitation, trauma, disposition |
| Inpatient / Ward | E | Ward rounds, nursing notes, fluid balance, transfers |
| ICU / HDU | E | IcuAdmission, critical care charts, ventilators, sedation |
| Maternity | E | ANC, pregnancy, labour, delivery, postnatal |
| Neonatal / NICU | E | Newborns, NICU admission, phototherapy, feeds |
| Theatre / OT | E | Bookings, WHO checklist, preop, recovery, teams |
| Anaesthesia | E | Assessments, records, drugs, intraop vitals, PACU review |
| Blood Bank | E | Donors, units, crossmatch, transfusion, reactions |
| CSSD | E | Instruments, decontamination flow, cycles, QC, issue/return |
| Mortuary | E | Admission, identification, storage, release, death certificate |
| Ambulance | E | Vehicles, calls, trips, fuel, maintenance |
| Maintenance / Biomedical | E | Assets, work orders, calibration, downtime |
| Security | E | Incidents, visitors, access events, biometric |
| Social Work | E | Assessments, waivers, discharge plans |
| Dietetics | E | Diet orders, nutrition records, meal orders |
| Mental Health | E | Assessments, treatment plans, counselling sessions |
| HIV / TB / Oncology | E | HTS, ART, viral load, TB program, cancer/chemo |
| Paediatrics | E | Growth, developmental assessment, immunization, child protection |
| Public Health | E | Immunization schedules, FP, surveillance, outbreaks |

---

## CSSD Conceptual Workflow

Instrument -> Decontamination -> Packing -> Sterilization -> QC -> Storage -> Issue -> Return

| Step | Status |
|---|---|
| Instruments | E (CssdInstrument) |
| Decontamination | P (batch flow present; explicit decontamination step partial) |
| Packing | P |
| Sterilization / cycles | E (CssdCycleRecord, SterilizerRunController) |
| QC / indicators | E (SterilityIndicatorController) |
| Storage | E |
| Issue | E (CssdIssueController) |
| Return | E (returnSet route) |
| Traceability | P (issue/return records; full chain unverified) |

---

## Laboratory Result Separation

| Action | Permission | Status |
|---|---|---|
| Result entry | enter test results (sidebar) / manage lab worklists | E |
| Result validation | verify lab results | E |
| Result release | print/download lab reports | E |
| Amendments | Partial | verify/approve flow exists; dedicated amend permission naming differs |

Spec asks to separate result.entry / result.validate / result.release where applicable — project uses human-readable equivalents; separation exists via distinct permissions.

---

## Radiology Permissions

| Role concern | Status |
|---|---|
| Technician vs radiologist permissions not identical | Partial | approve radiology reports vs manage radiology worklist/schedules |

---

## Pharmacy Deletion Rule

| Rule | Status |
|---|---|
| Avoid hard-delete for dispensing/medication history | Partial | Controlled correction workflows not fully verified; dispensing models exist |

---

## Clinical Record Immutability

| Rule | Status |
|---|---|
| Finalized records corrected via amendment not silent destroy | Partial | Audit columns added; amend workflows vary by module |
| Amendment preserves original + reason + audit | Partial | Auditable trait now records changes |

---

## Reporting Coverage

| Category | Status |
|---|---|
| Clinical reports | E (patient, diagnosis, lab, radiology, admissions, maternity, birth, death, ICU, theatre) |
| Financial reports | E (revenue, billing, payments, P&L, balance sheet, cash flow, claims) |
| Operational reports | E (bed occupancy, doctor performance, pharmacy, inventory, blood bank, ambulance, maintenance) |
| HR reports | E (staff, attendance, leave, payroll, headcount, attrition, training) |
| KHIS / MOH | E (KhisReportController, MohReportsController) |
| Authorization scope on reports | Partial | Reports exist; scope enforcement incomplete |

---

## Workflow Testing Status

| Area | Test evidence |
|---|---|
| Triage | G005TriageCompletionTest |
| Emergency | G011EmergencyCompletionTest |
| Maternity | G014MaternityTest |
| Neonatal | G015NeonatalTest |
| Inpatient | G017InpatientCompletionTest |
| ICU | G019IcuTest |
| Theatre | G020TheatreCompletionTest |
| Anaesthesia | G022AnaesthesiaTest |
| Lab | G025LabCompletionTest |
| Radiology | G028RadiologyCompletionTest |
| Blood Bank | G031BloodBankCompletionTest |
| Pharmacy | G034PharmacyCompletionTest |
| HIV | G037HivTest |
| TB | G038TbTest |
| Oncology | G039OncologyTest |
| Mental Health / Social | G042MentalHealthSocialWorkTest |
| Mortuary | G043MortuaryCompletionTest |
| Ambulance | G045AmbulanceCompletionTest |
| CSSD | G047CssdCompletionTest |
| Maintenance | G050MaintenanceCompletionTest |
| Security | G054SecurityCompletionTest |
| Billing | G059BillingCompletionTest |
| SHA | G062ShaCompletionTest |
| Finance | G067FinanceJournalsTest |
| HR | G070HrCompletionTest |
| Appointments/Referrals | G075AppointmentsReferralsTest |
| Telemedicine/Comms | G079TelemedicineCommsTest |
| Audit/Compliance | G086AuditComplianceTest |
| Dashboard | G089DashboardCompletionTest |

---

## Conclusion

Clinical workflows are largely EXISTING at the module level. Genuine gaps are enforcement depth (scope, policy adoption in controllers, own-records isolation), immutability/amendment completeness, and external SHA/DHA credentials. No new clinical modules were invented this session.
