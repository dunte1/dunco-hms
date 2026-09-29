# Dunco HMS: master module matrix and gaps audit

Target: Kenya Level 6 (national referral and teaching) hospital. One patient record across all departments.

This file has four parts:

1. The audit prompt for opencode (section 1)
2. Conventions used in the matrix (section 2)
3. The master matrix, 54 modules (section 3)
4. Findings, filled in by the audit (section 4)

---

## 1. Audit prompt for opencode

Copy everything inside the block below into opencode. Run it from the project root.

```text
You are auditing this repository against the master HMS module matrix in ./gaps-audit.md.

Read sections 2 and 3 of gaps-audit.md in full before doing anything else.

RULES
- Do not modify application code, migrations, or config. The only file you may edit is gaps-audit.md, and only section 4 (Findings). Never edit sections 1 to 3.
- Every status must cite evidence: file path and line range, or a migration name. No evidence means the status is MISSING.
- Do not assume. If a table, route, or permission is not found in the code, report it as not found.
- Match by meaning, not only by name. A table called `pt_visits` satisfies `visits`. Record the actual name and mark it DIVERGENT if the shape differs materially from the matrix.
- Treat UI-only screens with hard-coded or mock data as STUB.

STATUS VALUES
- OK: implemented and wired end to end for that dimension
- PARTIAL: some of it exists; list exactly what is absent
- STUB: UI or route exists but returns mock data or does nothing
- DIVERGENT: exists under a different name or design; note the mapping
- MISSING: not found

PHASE 0: DISCOVER THE STACK (write results to section 4.0)
1. Identify language, framework, ORM, database, frontend framework, auth method, queue/job system, test framework.
2. Locate: migrations or schema files, models/entities, routes/controllers, services, permission/role seeds, UI pages or components, report definitions, scheduled jobs, integration clients, tests.
3. Produce an inventory: full list of DB tables, full list of API routes, full list of permissions, full list of roles, full list of UI pages. Save counts and lists in section 4.0.
4. Note the multi-tenancy model (single facility, multi-branch, multi-tenant) and whether it is enforced in queries.

PHASE 1: MODULE AUDIT (repeat for every module M01 to M54 in section 3)
For each module, check seven dimensions against the matrix row:
1. Schema: each listed table exists, with the expected foreign keys (patient_id, visit_id or encounter_id where the matrix says so), timestamps, soft delete, and created_by/updated_by.
2. Functions: each listed function is implemented in service or controller code.
3. Roles: each listed role exists and is assigned to the module.
4. Permissions: each listed permission exists in the seed AND is enforced server-side on the matching route. A permission that is only checked in the UI counts as PARTIAL.
5. Workflows: each listed workflow can be traced through code from start to end, including status transitions and side effects (billing charge, stock decrement, notification, audit log).
6. Reports: each listed report has a query or endpoint and a UI or export.
7. APIs: each listed endpoint exists, with validation, auth, and pagination on list endpoints.
Also record: UI pages present, automated tests present, and any code in the repo for this module that is not in the matrix (EXTRA).

Work in batches by domain (A to K in section 3). After each batch, write that batch's results into section 4 before starting the next. If context runs low, stop after a batch and say which module you stopped at, so the run can resume.

PHASE 2: CROSS-CUTTING CHECKS (write to section 4.2)
Verify each and cite evidence:
- One patient record: every clinical table links to patients and to a visit or encounter. Identify any department that keeps its own patient copy.
- Patient journey: trace the flow in section 3.60 from registration to follow-up. Mark every hop as OK, PARTIAL, or BROKEN, and name the file where it breaks.
- RBAC enforced on the server for every route. List routes with no auth or no permission check.
- Audit log covers create, update, delete, and read of patient records, with user, time, and IP.
- Money and stock: billing, payments, refunds, and stock movements run inside DB transactions and are idempotent on retry.
- Integrations: SHA, M-Pesa (STK push and callback), SMS, email, WhatsApp, KHIS/DHIS2, PACS/DICOM, lab analyzers (HL7 or ASTM). Report each as OK, STUB, or MISSING.
- Data protection: consent capture, access logging, field-level protection for HIV, mental health, and other sensitive records, retention rules, backup and restore.
- Numbering: MRN, visit, invoice, receipt, lab, and claim number sequences are unique and concurrency safe.
- Notifications and queues run as background jobs with retry.
- Configuration: every item in module M58 is admin-editable, not hard-coded.
- Tests: coverage by module; list modules with none.

PHASE 3: OUTPUT (write to section 4.1, 4.3, 4.4, 4.5)
1. Scoreboard (4.1): one row per module with a status for each of the seven dimensions and an overall percent.
2. Gap register (4.3): one row per gap: ID, module, dimension, description, severity (P0 blocks a patient journey or is a safety/legal risk, P1 core feature missing, P2 secondary), estimated effort (S under 1 day, M 1 to 5 days, L over 5 days), dependencies.
3. Suggested build order (4.4): sequence by dependency, starting with anything that breaks the patient journey.
4. Open questions (4.5): anything you could not determine from the code.

When finished, print a summary in the terminal: counts by status, top 10 P0 gaps, and the path to section 4.
```

---

## 2. Conventions

### 2.1 Role codes

| Code | Role | Code | Role |
|---|---|---|---|
| ADM | System administrator | MGT | Executive / hospital manager |
| REC | Receptionist / registration clerk | TRG | Triage nurse |
| NUR | Nurse | MID | Midwife |
| DOC | Medical officer | SPC | Specialist / consultant |
| SUR | Surgeon | ANA | Anaesthetist |
| PHM | Pharmacist | PHT | Pharmaceutical technologist |
| LAB | Laboratory technologist | PTH | Pathologist |
| RAD | Radiologist | RDG | Radiographer / sonographer |
| BBT | Blood bank technologist | CSH | Cashier |
| ACC | Accountant | CLM | Claims officer |
| STO | Storekeeper | PRO | Procurement officer |
| HRM | HR officer | BME | Biomedical engineer |
| MNT | Maintenance technician | MRO | Medical records officer |
| IPC | Infection control officer | SWK | Social worker |
| NUT | Nutritionist / dietitian | PHY | Physiotherapist |
| OTH | Occupational therapist | MHP | Mental health professional |
| DEN | Dental officer | OPH | Ophthalmologist / optometrist |
| CSS | CSSD technician | DRV | Ambulance driver / crew |
| MOR | Mortuary attendant | SEC | Security officer |
| HSK | Housekeeping / laundry supervisor | KIT | Kitchen supervisor |
| ICT | ICT officer | QAO | Quality officer |
| AUD | Auditor | RSC | Research / training coordinator |
| PAT | Patient (portal user) | CHW | Public health / programme officer |

### 2.2 Shared core tables (every module depends on these)

`facilities`, `branches`, `departments`, `users`, `roles`, `permissions`, `role_permissions`, `user_roles`, `patients`, `visits`, `encounters`, `queue_tickets`, `orders`, `order_items`, `results`, `clinical_notes`, `documents`, `services`, `price_items`, `charges`, `invoices`, `payments`, `notifications`, `number_sequences`, `settings`, `audit_logs`, `attachments`.

Standard columns on every table: `id`, `facility_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`. Clinical tables also carry `patient_id` and `visit_id` or `encounter_id`.

### 2.3 Permission naming

Format: `module.resource.action`. Standard actions: `view`, `create`, `update`, `delete`, `approve`, `print`, `export`. Braces in the matrix expand: `lab.result.{enter,verify}` means `lab.result.enter` and `lab.result.verify`.

Every module also has `module.report.view` and `module.report.export`. These are not repeated per row.

### 2.4 API naming

Base path `/api/v1`. REST resources, plural nouns. Every resource in the Tables column is expected to have list, get, create, update endpoints unless noted. The APIs column lists only the non-obvious endpoints. List endpoints need pagination, filtering, and auth.

### 2.5 Status columns for the audit

OK, PARTIAL, STUB, DIVERGENT, MISSING (defined in section 1).

---

## 3. Master matrix

### Module index

| ID | Module | Domain | Priority |
|---|---|---|---|
| M01 | Registration and MPI | A Front office | P0 |
| M02 | Triage | A | P0 |
| M03 | OPD / general consultation | A | P0 |
| M04 | Emergency and casualty | A | P0 |
| M05 | Specialist outpatient clinics | A | P1 |
| M06 | Maternity, obstetrics and gynaecology | B Inpatient and critical care | P0 |
| M07 | Neonatal unit | B | P1 |
| M08 | Paediatrics | B | P1 |
| M09 | Inpatient and wards | B | P0 |
| M10 | ICU and HDU | B | P1 |
| M11 | Operating theatre | B | P1 |
| M12 | Anaesthesia | B | P1 |
| M13 | Nursing management | B | P1 |
| M14 | Laboratory | C Diagnostics | P0 |
| M15 | Radiology and imaging | C | P0 |
| M16 | Blood bank | C | P1 |
| M17 | Pharmacy | D Medication | P0 |
| M18 | HIV testing services and HIV care | E Programmes | P1 |
| M19 | TB clinic | E | P1 |
| M20 | Oncology | E | P1 |
| M21 | Public health programmes | E | P1 |
| M22 | Dental and oral health | F Allied and specialty | P2 |
| M23 | Ophthalmology | F | P2 |
| M24 | ENT and audiology | F | P2 |
| M25 | Physiotherapy and occupational therapy | F | P2 |
| M26 | Nutrition and dietetics | F | P2 |
| M27 | Mental health | F | P1 |
| M28 | Social work and patient welfare | F | P2 |
| M29 | Mortuary | G Operations | P1 |
| M30 | Ambulance and patient transport | G | P1 |
| M31 | CSSD | G | P1 |
| M32 | Infection prevention and control | G | P1 |
| M33 | Maintenance, biomedical engineering and assets | G | P1 |
| M34 | Linen, laundry and housekeeping | G | P2 |
| M35 | Kitchen and catering | G | P2 |
| M36 | Security and visitor management | G | P2 |
| M37 | Fire, safety and occupational health | G | P2 |
| M38 | Medical records and health information | H Business | P0 |
| M39 | Billing and cash office | H | P0 |
| M40 | SHA and insurance | H | P0 |
| M41 | Procurement and central stores | H | P1 |
| M42 | Finance and accounting | H | P1 |
| M43 | Human resources | I People | P1 |
| M44 | Medical staff credentialing | I | P1 |
| M45 | Appointments and queue engine | J Access and digital | P0 |
| M46 | Referral management | J | P0 |
| M47 | Telemedicine and patient portal | J | P2 |
| M48 | Patient communication | J | P1 |
| M49 | Research and training | K Management and system | P2 |
| M50 | Quality assurance, feedback and complaints | K | P1 |
| M51 | Audit and compliance | K | P0 |
| M52 | ICT department | K | P2 |
| M53 | Management dashboard and analytics | K | P1 |
| M54 | Master configuration, users, roles, integrations | K | P0 |

Priority is a starting suggestion. P0 modules are on the core patient journey (section 3.60) or carry legal or safety risk.

---

### Domain A: front office and outpatient

#### M01 Registration and MPI
- Functions: new and returning patient registration; patient search; MRN generation; biometric identification; demographics; next of kin; emergency (unknown patient) registration; appointment and referral registration; transfer-in; duplicate detection; patient merge.
- Tables: `patients`, `patient_identifiers`, `patient_contacts`, `patient_biometrics`, `duplicate_candidates`, `patient_merge_log`, `registration_types`
- Roles: REC, MRO, ADM
- Permissions: `patient.{view,create,update,print}`, `patient.search`, `patient.merge`, `patient.emergency.register`, `patient.biometric.{enroll,verify}`
- Workflows: search, then match, then register or reuse existing. Unknown patient gets a temporary ID, then reconciled, then merged. Duplicate flagged, reviewed, merged; merge is reversible and logged.
- Reports: registrations by day, user, type; duplicate and merge log; registrations by payer.
- APIs: `POST /patients/emergency`, `GET /patients?q=`, `POST /patients/{id}/merge`, `POST /patients/{id}/biometrics/verify`
- Depends on: M54

#### M02 Triage
- Functions: patient queue; vitals (temperature, BP, pulse, respiratory rate, SpO2, weight, height, BMI); pain score; GCS; pregnancy status; allergies; chief complaint; emergency classification; triage category; nurse notes; priority queue; escalation; triage history.
- Tables: `triage_records`, `vital_signs`, `triage_categories`, `allergies`, `triage_escalations`
- Roles: TRG, NUR, DOC
- Permissions: `triage.record.{view,create,update}`, `triage.escalate`, `triage.queue.view`
- Workflows: registered, then queued, then vitals, then category assigned, then routed to OPD or emergency. Critical vitals trigger escalation.
- Reports: triage category mix, time to triage, escalations.
- APIs: `POST /triage`, `GET /triage/queue`, `POST /triage/{id}/escalate`
- Depends on: M01, M45

#### M03 OPD / general consultation
- Functions: consultation queue; chief complaint; history; examination; diagnosis (ICD); differential diagnosis; clinical notes; medical and medication history; investigation requests; prescription; procedures; referral; follow-up; sick note; medical certificate; OPD discharge.
- Tables: `consultations`, `diagnoses`, `clinical_notes`, `medical_history`, `medication_history`, `investigation_orders`, `prescriptions`, `prescription_items`, `procedure_orders`, `sick_notes`, `medical_certificates`
- Roles: DOC, SPC, NUR
- Permissions: `consult.{view,create,update,close}`, `consult.diagnose`, `order.lab.create`, `order.radiology.create`, `rx.create`, `certificate.issue`
- Workflows: queue, then consult, then orders to lab/radiology, then results reviewed, then prescription to pharmacy, then charges to billing, then discharge or admit or refer.
- Reports: OPD attendance, top diagnoses (ICD), consultations per clinician, average consultation time.
- APIs: `POST /consultations`, `POST /consultations/{id}/orders`, `POST /consultations/{id}/prescriptions`, `POST /consultations/{id}/close`
- Depends on: M02, M14, M15, M17, M39

#### M04 Emergency and casualty
- Functions: emergency registration; emergency triage; resuscitation; trauma; accident management; emergency consultation, procedures, medication; emergency lab and imaging requests; observation; ambulance intake; admission; theatre and ICU transfer; referral; emergency discharge; death documentation; emergency billing.
- Tables: `emergency_visits`, `resuscitation_records`, `trauma_assessments`, `emergency_procedures`, `observation_stays`, `emergency_dispositions`, `death_records`
- Roles: DOC, SPC, TRG, NUR, DRV
- Permissions: `emergency.visit.{view,create,update}`, `emergency.resus.record`, `emergency.disposition.set`, `emergency.death.certify`, `emergency.billing.defer`
- Workflows: arrival (walk-in or ambulance), then triage, then resuscitation or treatment, then disposition (discharge, admit, theatre, ICU, refer, death). Registration and billing can be completed after stabilisation.
- Reports: emergency attendance, trauma mix, time to disposition, deaths, ambulance arrivals.
- APIs: `POST /emergency/visits`, `POST /emergency/visits/{id}/disposition`
- Depends on: M01, M09, M10, M11, M30, M46

#### M05 Specialist outpatient clinics
- Functions: configurable clinics (medical: internal medicine, cardiology, neurology, nephrology, gastroenterology, endocrinology, pulmonology, rheumatology, infectious diseases, dermatology, psychiatry, oncology, haematology, geriatrics; surgical: general, orthopaedics, neurosurgery, cardiothoracic, urology, ENT, ophthalmology, plastic, paediatric, maxillofacial); specialist appointments; clinic queue; consultation; investigations; procedures; prescription; follow-up; referral; clinic statistics.
- Tables: `clinics`, `clinic_sessions`, `clinic_templates`, `clinic_appointments`, `specialist_consultations`, `clinic_forms`
- Roles: SPC, DOC, NUR, REC
- Permissions: `clinic.{view,create,update}`, `clinic.session.manage`, `clinic.consult.{create,update}`, `clinic.template.manage`
- Workflows: referral or booking, then clinic session, then consult using clinic-specific template, then orders, then follow-up booking.
- Reports: clinic load, waiting time, no-show rate, top diagnoses per clinic.
- APIs: `GET /clinics/{id}/sessions`, `POST /clinics/{id}/appointments`
- Depends on: M03, M45, M46

### Domain B: inpatient and critical care

#### M06 Maternity, obstetrics and gynaecology
- Functions: ANC registration and visits; pregnancy history; gravida/para; EDD calculation; high-risk flagging; obstetric ultrasound; maternal observations; labour management; partograph; delivery (normal, assisted, caesarean); postnatal care; family planning; gynaecology clinic and procedures; maternal emergencies; maternal discharge; maternal mortality reporting.
- Tables: `anc_registrations`, `anc_visits`, `pregnancies`, `labour_records`, `partograph_entries`, `deliveries`, `postnatal_visits`, `gyn_procedures`, `maternal_deaths`, `family_planning_visits`
- Roles: MID, DOC, SPC, NUR, RDG
- Permissions: `maternity.anc.{view,create,update}`, `maternity.labour.{view,record}`, `maternity.delivery.record`, `maternity.pnc.record`, `maternity.mortality.report`
- Workflows: ANC registration, then visits, then admission in labour, then partograph, then delivery, then newborn record created and linked, then postnatal, then discharge. Caesarean routes through M11.
- Reports: deliveries by mode, ANC coverage, maternal deaths, stillbirths, partograph alerts.
- APIs: `POST /maternity/anc`, `POST /maternity/labour/{id}/partograph`, `POST /maternity/deliveries` (creates newborn)
- Depends on: M07, M09, M11, M15

#### M07 Neonatal unit
- Functions: newborn registration; birth records; APGAR; neonatal assessment; incubator management; phototherapy; feeding; neonatal medication (weight-based); vitals; NICU admission and discharge; procedures; investigations; maternal-newborn linkage; growth monitoring.
- Tables: `newborns`, `birth_records`, `apgar_scores`, `neonatal_assessments`, `nicu_admissions`, `incubator_assignments`, `phototherapy_sessions`, `neonatal_feeds`
- Roles: NUR, MID, DOC, SPC
- Permissions: `neonatal.{view,create,update}`, `neonatal.nicu.{admit,discharge}`, `neonatal.birth.register`
- Workflows: delivery creates newborn, then assessment, then well-baby or NICU. NICU admission allocates bed and incubator. Discharge links to immunization and growth follow-up.
- Reports: births, low birth weight, NICU admissions and outcomes, neonatal mortality.
- APIs: `POST /neonatal/newborns`, `POST /neonatal/nicu/admissions`
- Depends on: M06, M09, M17

#### M08 Paediatrics
- Functions: paediatric registration; growth charts; developmental assessment; immunization; consultation; emergency; inpatient; subspecialty clinics; weight-based dosing; nutrition; child protection; discharge; follow-up.
- Tables: `growth_measurements`, `developmental_assessments`, `immunization_records`, `paediatric_consultations`, `child_protection_cases`, `dosing_rules`
- Roles: DOC, SPC, NUR, SWK
- Permissions: `paeds.{view,create,update}`, `paeds.growth.record`, `paeds.childprotection.{view,create}` (restricted)
- Workflows: consult, then growth plotted, then dose calculated from weight, then prescription. Child protection concerns open a restricted case linked to M28.
- Reports: growth faltering, immunization defaulters, paediatric admissions.
- APIs: `POST /paediatrics/growth`, `GET /paediatrics/dose-calc`
- Depends on: M03, M09, M17, M21, M28

#### M09 Inpatient and wards
- Functions: ward and bed management; admission; bed allocation and transfer; ward rounds; nursing and doctor notes; vitals; fluid balance; intake/output; medication administration (MAR); care plans; procedures; investigations; diet orders; discharge; transfer; death; discharge summary. Ward types: medical, surgical, paediatric, maternity, gynaecology, orthopaedic, private, isolation, specialist.
- Tables: `wards`, `rooms`, `beds`, `admissions`, `bed_assignments`, `ward_rounds`, `nursing_notes`, `fluid_balance_entries`, `medication_administrations`, `care_plans`, `diet_orders`, `discharge_summaries`, `inpatient_transfers`
- Roles: NUR, DOC, SPC, NUT, ADM
- Permissions: `ipd.admission.{view,create,update}`, `ipd.bed.{allocate,transfer}`, `ipd.round.record`, `ipd.mar.administer`, `ipd.discharge.{initiate,approve}`, `ipd.summary.sign`
- Workflows: admission order, then bed allocated, then charges start (daily bed), then rounds and orders, then MAR, then discharge order, then summary signed, then clearance (billing and pharmacy), then bed released.
- Reports: occupancy, length of stay, admissions and discharges, mortality, bed turnover.
- APIs: `POST /admissions`, `POST /admissions/{id}/transfer`, `POST /admissions/{id}/discharge`, `GET /beds/availability`
- Depends on: M03, M04, M17, M39, M35

#### M10 ICU and HDU
- Functions: ICU/HDU admission; bed allocation; ventilator management; vital monitoring; GCS; sedation; infusions; fluid balance; ABG; critical care charts; nursing observations; MAR; procedures; lab and imaging integration; rounds; oxygen; billing; transfer and discharge; mortality.
- Tables: `icu_admissions`, `ventilator_settings`, `critical_care_charts`, `infusion_records`, `abg_results`, `sedation_scores`, `icu_rounds`, `severity_scores`
- Roles: NUR, DOC, SPC, ANA
- Permissions: `icu.{view,admit,update,discharge}`, `icu.chart.record`, `icu.ventilator.record`
- Workflows: admit from emergency, ward, or theatre; hourly charting; rounds; step-down to HDU or ward; billing by unit-day plus consumables.
- Reports: ICU occupancy, ventilator days, ICU mortality, length of stay, readmission.
- APIs: `POST /icu/admissions`, `POST /icu/admissions/{id}/charts`
- Depends on: M09, M14, M15, M39

#### M11 Operating theatre
- Functions: theatre booking and schedule; surgical waiting list; pre-operative assessment; consent; WHO surgical safety checklist; team (surgeon, anaesthetist, theatre nurse); operation notes; instrument tracking; consumables; theatre drugs; specimens; recovery/PACU; post-op notes; utilization; cancellations; billing.
- Tables: `theatres`, `theatre_bookings`, `surgical_waiting_list`, `preop_assessments`, `consents`, `safety_checklists`, `operation_notes`, `theatre_teams`, `theatre_consumables`, `specimens`, `recovery_records`
- Roles: SUR, ANA, NUR, CSS
- Permissions: `theatre.booking.{view,create,cancel}`, `theatre.consent.record`, `theatre.checklist.complete`, `theatre.opnote.{create,sign}`, `theatre.consumable.record`
- Workflows: booking, then pre-op assessment, then consent, then checklist (sign-in, time-out, sign-out), then operation, then specimen to lab, then recovery, then ward. Instrument sets issued and returned via M31. Consumables decrement stock and post charges.
- Reports: theatre utilization, cancellations with reasons, turnover time, procedures by surgeon, waiting list ageing.
- APIs: `POST /theatre/bookings`, `POST /theatre/bookings/{id}/checklist`, `POST /theatre/cases/{id}/opnote`
- Depends on: M12, M31, M14, M41, M39

#### M12 Anaesthesia
- Functions: pre-anaesthetic assessment; anaesthesia plan; ASA classification; airway assessment; anaesthetic drugs; intra-operative monitoring; anaesthesia chart; recovery; complications; post-anaesthetic review.
- Tables: `anaesthesia_assessments`, `anaesthesia_records`, `anaesthesia_drugs_given`, `intraop_vitals`, `anaesthesia_complications`, `postanaesthesia_reviews`
- Roles: ANA, NUR
- Permissions: `anaesthesia.{view,create,update,sign}`
- Workflows: pre-anaesthetic review, then plan, then intra-op record linked to theatre case, then recovery scoring, then post-op review.
- Reports: ASA mix, complications, anaesthesia by type.
- APIs: `POST /anaesthesia/records`, `POST /anaesthesia/records/{id}/vitals`
- Depends on: M11

#### M13 Nursing management
- Functions: nurse allocation; duty roster; shift handover; nursing notes; care plans; MAR; vitals; intake/output; nursing procedures; ward rounds; workload; nurse reports.
- Tables: `nurse_allocations`, `duty_rosters`, `shift_handovers`, `nursing_workload_scores`, `nursing_procedures`
- Roles: NUR, MGT
- Permissions: `nursing.roster.{view,manage}`, `nursing.handover.{create,view}`, `nursing.allocation.manage`
- Workflows: roster published, then allocation per shift, then handover with outstanding tasks, then workload review.
- Reports: nurse-patient ratio, workload, handover completion.
- APIs: `POST /nursing/handovers`, `GET /nursing/roster`
- Depends on: M09, M43

### Domain C: diagnostics

#### M14 Laboratory
- Functions: reception; sample collection, labelling, tracking; test requests; processing; results; result verification; pathologist approval; critical results; QC; reagents and consumables; equipment; worklists; turnaround time; billing. Departments: haematology, biochemistry, microbiology, immunology, serology, histopathology, cytology, molecular, blood grouping, parasitology, clinical microscopy, genetics.
- Tables: `lab_test_catalog`, `lab_panels`, `lab_orders`, `lab_specimens`, `lab_worklists`, `lab_results`, `lab_result_verifications`, `lab_critical_alerts`, `lab_qc_runs`, `lab_reagents`, `lab_equipment`, `lab_reference_ranges`, `lab_analyzer_messages`
- Roles: LAB, PTH, DOC, NUR
- Permissions: `lab.order.{view,create}`, `lab.specimen.{collect,receive,reject}`, `lab.result.{enter,verify,approve,amend}`, `lab.critical.acknowledge`, `lab.qc.record`, `lab.catalog.manage`
- Workflows: order, then charge, then collection with barcode, then receive, then worklist, then result entry or analyzer import, then verify, then pathologist approval where required, then release to clinician and portal. Critical result requires acknowledgement.
- Reports: turnaround time, workload by department, rejected specimens, QC, critical results, reagent consumption.
- APIs: `POST /lab/orders`, `POST /lab/specimens/{id}/receive`, `POST /lab/results/{id}/verify`, `POST /lab/analyzers/messages`
- Depends on: M03, M09, M39, M41

#### M15 Radiology and imaging
- Functions: imaging requests; scheduling; queue; X-ray, ultrasound, CT, MRI, mammography, fluoroscopy, interventional; image storage; radiologist reporting and approval; imaging history; PACS and DICOM integration; billing.
- Tables: `imaging_catalog`, `imaging_orders`, `imaging_schedules`, `imaging_studies`, `imaging_reports`, `imaging_report_versions`, `modality_worklist`, `contrast_records`
- Roles: RDG, RAD, DOC
- Permissions: `imaging.order.{view,create}`, `imaging.schedule.manage`, `imaging.study.perform`, `imaging.report.{create,approve,amend}`, `imaging.image.view`
- Workflows: order, then schedule, then modality worklist, then study performed, then images to PACS, then report drafted, then radiologist approval, then release.
- Reports: turnaround time, studies by modality, radiation dose log, pending reports.
- APIs: `POST /imaging/orders`, `POST /imaging/studies/{id}/report`, `GET /imaging/studies/{id}/viewer-link`, DICOM/HL7 endpoints
- Depends on: M03, M09, M39, M45

#### M16 Blood bank
- Functions: donors; collection; blood groups; blood units; screening; cross-matching; reservation; issue; transfusion; reactions; expiry; inventory; wastage; traceability.
- Tables: `blood_donors`, `blood_donations`, `blood_units`, `blood_screening_results`, `crossmatch_requests`, `blood_issues`, `transfusions`, `transfusion_reactions`, `blood_wastage`
- Roles: BBT, LAB, DOC, NUR
- Permissions: `bloodbank.donor.{view,create}`, `bloodbank.unit.{receive,discard}`, `bloodbank.crossmatch.perform`, `bloodbank.issue.approve`, `bloodbank.transfusion.record`, `bloodbank.reaction.report`
- Workflows: donation, then screening, then unit available, then request, then crossmatch, then reserve, then issue, then transfusion with bedside check, then reaction reporting. Every unit traceable donor to recipient.
- Reports: stock by group and expiry, wastage, issues, reactions, donor deferrals.
- APIs: `POST /bloodbank/requests`, `POST /bloodbank/units/{id}/issue`, `POST /bloodbank/transfusions`
- Depends on: M14, M09, M11

### Domain D: medication

#### M17 Pharmacy
- Functions: dashboard; drug catalogue; stock, batches, expiry; GRN; suppliers; purchase orders; dispensing; prescription queue; inpatient and outpatient dispensing; returns; stock adjustments and transfers; controlled drugs; reordering; pricing; pharmacist verification.
- Tables: `drugs`, `drug_batches`, `pharmacy_stores`, `stock_movements`, `dispensations`, `dispensation_items`, `drug_returns`, `controlled_drug_register`, `drug_prices`, `drug_interactions`, `reorder_levels`
- Roles: PHM, PHT, DOC, NUR, STO
- Permissions: `pharmacy.rx.{view,verify,dispense}`, `pharmacy.stock.{view,adjust,transfer}`, `pharmacy.grn.receive`, `pharmacy.controlled.{dispense,reconcile}`, `pharmacy.price.manage`, `pharmacy.return.approve`
- Workflows: prescription, then pharmacist verification (allergy, interaction, dose check), then dispense from batch (FEFO), then stock decrement and charge. Inpatient orders feed ward-level dispensing. Controlled drugs need two signatures.
- Reports: stock on hand, near-expiry, consumption, controlled drug register, dispensing by pharmacist, stock-outs.
- APIs: `POST /pharmacy/prescriptions/{id}/verify`, `POST /pharmacy/prescriptions/{id}/dispense`, `POST /pharmacy/grn`, `POST /pharmacy/stock/adjust`
- Depends on: M03, M09, M39, M41

### Domain E: public health and disease programmes

#### M18 HIV testing services and HIV care
- Functions: HTS registration; risk assessment; pre-test information; consent; HIV test; results; post-test counselling; partner notification; linkage; ART referral; PEP; PrEP; HIV care; HIV-exposed infants; viral load; CD4; follow-up; confidential records; reporting.
- Tables: `hts_encounters`, `hts_risk_assessments`, `hiv_tests`, `hiv_care_enrollments`, `art_regimens`, `partner_notifications`, `linkage_records`, `pep_prep_records`, `hei_records`, `viral_load_results`
- Roles: CHW, NUR, DOC, LAB
- Permissions: `hiv.hts.{view,create}`, `hiv.result.disclose`, `hiv.care.{view,update}`, `hiv.confidential.view` (restricted, logged)
- Workflows: pre-test, then consent, then test, then result, then post-test counselling, then linkage or prevention, then follow-up. Access to records is restricted and every read is logged.
- Reports: tests done, positivity, linkage rate, viral suppression, MOH/KHIS-format returns.
- APIs: `POST /hiv/hts`, `POST /hiv/hts/{id}/linkage`
- Depends on: M14, M17, M38

#### M19 TB clinic
- Functions: screening; diagnosis; GeneXpert; microscopy; culture; drug susceptibility; treatment; medication; adherence; contact tracing; TB/HIV integration; MDR-TB; follow-up; reporting.
- Tables: `tb_screenings`, `tb_cases`, `tb_lab_results`, `tb_regimens`, `tb_adherence_logs`, `tb_contacts`, `mdr_tb_cases`
- Roles: CHW, DOC, NUR, LAB
- Permissions: `tb.case.{view,create,update}`, `tb.treatment.manage`, `tb.contact.trace`
- Workflows: screen, then lab confirmation, then register case, then regimen, then monthly follow-up, then outcome. Contacts traced and screened. HIV status cross-referenced.
- Reports: case notification, treatment outcomes, cohort analysis, MDR-TB.
- APIs: `POST /tb/cases`, `POST /tb/cases/{id}/contacts`
- Depends on: M14, M17, M18

#### M20 Oncology
- Functions: cancer registration; consultation; diagnosis; staging; histology; treatment plans; chemotherapy protocols; drug preparation; infusion; cycles; side effects; radiotherapy referral/integration; follow-up; palliative care; cancer registry.
- Tables: `cancer_registrations`, `cancer_staging`, `oncology_treatment_plans`, `chemo_protocols`, `chemo_cycles`, `chemo_preparations`, `chemo_infusions`, `adverse_events`, `palliative_care_plans`
- Roles: SPC, PHM, NUR, PTH
- Permissions: `oncology.{view,create,update}`, `oncology.chemo.{prescribe,verify,prepare,administer}`, `oncology.registry.export`
- Workflows: diagnosis and staging, then plan, then cycle prescription (BSA dose), then pharmacist verification, then preparation, then infusion, then toxicity review, then next cycle.
- Reports: cancer registry, cases by site and stage, chemo cycles, outcomes.
- APIs: `POST /oncology/plans`, `POST /oncology/cycles/{id}/verify`
- Depends on: M14, M15, M17

#### M21 Public health programmes
- Functions: immunization; family planning; disease surveillance; notifiable disease reporting; outbreak alerts; health campaigns; programme registers.
- Tables: `vaccines`, `immunization_schedules`, `immunization_doses`, `fp_visits`, `surveillance_cases`, `notifiable_disease_reports`, `outbreak_events`
- Roles: CHW, NUR, IPC, DOC
- Permissions: `publichealth.immunization.{view,record}`, `publichealth.surveillance.{report,view}`, `publichealth.outbreak.manage`
- Workflows: dose due list, then administration recorded, then defaulter tracing. Notifiable diagnosis triggers surveillance report.
- Reports: immunization coverage, defaulters, FP uptake, weekly surveillance return.
- APIs: `POST /immunizations`, `POST /surveillance/reports`
- Depends on: M03, M08, M38

### Domain F: allied and specialty services

#### M22 Dental and oral health
- Functions: registration; consultation; dental chart; X-ray; extraction; filling; scaling; root canal; oral surgery; prosthetics; follow-up; billing.
- Tables: `dental_charts`, `dental_procedures`, `dental_consultations`, `dental_prosthetics`
- Roles: DEN, NUR
- Permissions: `dental.{view,create,update}`, `dental.chart.update`
- Workflows: consult, then chart, then procedure, then charge, then follow-up.
- Reports: procedures by type, attendance.
- APIs: `POST /dental/procedures`
- Depends on: M03, M39

#### M23 Ophthalmology
- Functions: eye examination; visual acuity; refraction; IOP; slit lamp; fundus; cataract, glaucoma, retina; procedures and surgery; optical services; follow-up.
- Tables: `eye_exams`, `refractions`, `iop_readings`, `ophthalmic_procedures`, `optical_orders`
- Roles: OPH, NUR
- Permissions: `eye.{view,create,update}`, `eye.optical.order`
- Workflows: exam, then diagnosis, then procedure or surgery booking (M11), then optical order, then follow-up.
- Reports: cataract surgeries, glaucoma register, refraction volumes.
- APIs: `POST /ophthalmology/exams`
- Depends on: M03, M11

#### M24 ENT and audiology
- Functions: consultation; audiology and hearing tests; nasal and throat examination; endoscopy; procedures; surgery; follow-up.
- Tables: `ent_consultations`, `audiometry_results`, `ent_endoscopies`, `ent_procedures`
- Roles: SPC, NUR
- Permissions: `ent.{view,create,update}`
- Workflows: consult, then audiometry or endoscopy, then procedure or theatre booking, then follow-up.
- Reports: audiometry volumes, ENT procedures.
- APIs: `POST /ent/audiometry`
- Depends on: M03, M11

#### M25 Physiotherapy and occupational therapy
- Functions: assessment; treatment plan; sessions; exercise plans; rehabilitation; progress notes; equipment; assistive devices; follow-up; billing.
- Tables: `rehab_assessments`, `rehab_plans`, `rehab_sessions`, `assistive_devices`
- Roles: PHY, OTH
- Permissions: `rehab.{view,create,update}`, `rehab.session.record`
- Workflows: referral, then assessment, then plan, then sessions with progress notes, then discharge.
- Reports: sessions per therapist, outcomes, device issue.
- APIs: `POST /rehab/plans`, `POST /rehab/sessions`
- Depends on: M03, M09, M39

#### M26 Nutrition and dietetics
- Functions: assessment; BMI; malnutrition screening; diet plans; therapeutic and inpatient diets; meal plans; counselling; paediatric and maternal nutrition; follow-up.
- Tables: `nutrition_assessments`, `malnutrition_screenings`, `diet_plans`, `therapeutic_diets`
- Roles: NUT, NUR
- Permissions: `nutrition.{view,create,update}`, `nutrition.diet.prescribe`
- Workflows: screen, then assess, then diet order sent to kitchen (M35), then review.
- Reports: malnutrition prevalence, diet orders.
- APIs: `POST /nutrition/assessments`, `POST /nutrition/diet-orders`
- Depends on: M09, M35

#### M27 Mental health
- Functions: assessment; psychiatric consultation; counselling; diagnosis; treatment; medication; risk assessment; follow-up; inpatient psychiatry; psychosocial support.
- Tables: `mh_assessments`, `mh_consultations`, `mh_risk_assessments`, `counselling_sessions`, `mh_treatment_plans`
- Roles: MHP, SPC, NUR
- Permissions: `mentalhealth.{view,create,update}`, `mentalhealth.confidential.view` (restricted, logged), `mentalhealth.risk.assess`
- Workflows: assess, then risk rating, then plan, then follow-up. High risk triggers alert and safeguards.
- Reports: caseload, diagnoses, follow-up adherence.
- APIs: `POST /mental-health/assessments`
- Depends on: M03, M09

#### M28 Social work and patient welfare
- Functions: social assessment; counselling; vulnerable patient management; financial assistance and waivers; child protection; family support; discharge planning; community referral; follow-up.
- Tables: `social_assessments`, `welfare_cases`, `waiver_requests`, `discharge_plans`, `community_referrals`
- Roles: SWK, MGT
- Permissions: `social.{view,create,update}`, `social.waiver.{request,approve}`
- Workflows: identify need, then assess, then waiver request, then approval, then billing adjustment (M39). Discharge plan before ward discharge.
- Reports: waivers granted, vulnerable cases, community referrals.
- APIs: `POST /social/waivers`
- Depends on: M09, M39

### Domain G: hospital operations

#### M29 Mortuary
- Functions: registration; body admission; identification; tagging; refrigeration slots; cause of death; postmortem; pathology linkage; release authorization; next of kin; billing; body release; inventory; death certificates.
- Tables: `mortuary_admissions`, `mortuary_slots`, `body_identifications`, `postmortems`, `release_authorizations`, `death_certificates`
- Roles: MOR, PTH, DOC, SEC
- Permissions: `mortuary.{view,admit,release}`, `mortuary.release.authorize`, `mortuary.postmortem.record`, `mortuary.certificate.issue`
- Workflows: death recorded in ward, then body admitted with tag, then slot allocated, then identified by next of kin, then bills cleared, then release authorized, then released.
- Reports: admissions, occupancy, unclaimed bodies, releases.
- APIs: `POST /mortuary/admissions`, `POST /mortuary/admissions/{id}/release`
- Depends on: M09, M04, M39

#### M30 Ambulance and patient transport
- Functions: ambulances; drivers; crew; dispatch; emergency and referral transport; vehicle tracking; fuel; maintenance; trip records; referral destination; patient handover.
- Tables: `ambulances`, `drivers`, `crews`, `dispatches`, `trips`, `fuel_logs`, `vehicle_maintenance`, `handover_records`
- Roles: DRV, NUR, ADM
- Permissions: `ambulance.dispatch.{view,create,update}`, `ambulance.trip.record`, `ambulance.vehicle.manage`
- Workflows: request, then dispatch, then trip, then handover at destination, then billing.
- Reports: trips, response time, fuel and maintenance cost.
- APIs: `POST /ambulance/dispatches`, `POST /ambulance/trips/{id}/handover`
- Depends on: M04, M46, M33

#### M31 CSSD
- Functions: instrument registration; decontamination; cleaning; packing; sterilization; sterilizer records; sterility monitoring; instrument sets; theatre issue; returns; traceability; maintenance.
- Tables: `instruments`, `instrument_sets`, `cssd_cycles`, `sterilizer_runs`, `sterility_indicators`, `cssd_issues`, `cssd_returns`
- Roles: CSS, NUR
- Permissions: `cssd.{view,create}`, `cssd.cycle.record`, `cssd.issue.record`
- Workflows: return, then decontaminate, then clean, then pack, then sterilise with indicator, then store, then issue to theatre, then trace to patient.
- Reports: cycles, failed indicators, set traceability.
- APIs: `POST /cssd/cycles`, `POST /cssd/issues`
- Depends on: M11

#### M32 Infection prevention and control
- Functions: infection surveillance; hospital-acquired infections; isolation; PPE and hand hygiene monitoring; sterilization monitoring; outbreak management; antibiotic surveillance; audits.
- Tables: `hai_cases`, `isolation_records`, `hand_hygiene_audits`, `ipc_audits`, `outbreaks`, `antibiotic_usage`
- Roles: IPC, NUR, DOC
- Permissions: `ipc.{view,create,update}`, `ipc.outbreak.declare`, `ipc.audit.record`
- Workflows: culture or clinical flag, then HAI case, then isolation order to ward, then outbreak declaration, then reporting.
- Reports: HAI rates, hand hygiene compliance, outbreak logs, antibiotic use.
- APIs: `POST /ipc/hai-cases`, `POST /ipc/outbreaks`
- Depends on: M14, M09, M21

#### M33 Maintenance, biomedical engineering and assets
- Functions: maintenance requests; work orders; preventive and corrective maintenance; electrical, plumbing, HVAC, generator, solar, water, building, fire systems, elevators, security systems; medical equipment register; service history; calibration and certificates; breakdowns; repairs; spare parts; warranty; service contracts; downtime; location and assignment; vendors; asset register, tagging, transfer, disposal, depreciation, audit.
- Tables: `maintenance_requests`, `work_orders`, `pm_schedules`, `equipment`, `equipment_service_history`, `calibrations`, `breakdowns`, `spare_parts`, `service_contracts`, `warranties`, `vendors`, `assets`, `asset_transfers`, `asset_disposals`, `asset_depreciation`, `asset_audits`
- Roles: BME, MNT, ADM, ACC
- Permissions: `maint.request.{create,view}`, `maint.workorder.{assign,update,close}`, `maint.pm.manage`, `biomed.equipment.{view,manage}`, `biomed.calibration.record`, `asset.{view,create,transfer,dispose,audit}`
- Workflows: request, then work order, then assign, then repair with parts, then close and verify. PM schedule generates work orders. Calibration due alerts. Breakdown updates downtime.
- Reports: downtime by equipment, PM compliance, cost by asset, warranty expiry, calibration due, asset register.
- APIs: `POST /maintenance/requests`, `POST /maintenance/work-orders/{id}/close`, `POST /biomed/equipment/{id}/calibrations`
- Depends on: M41, M42

#### M34 Linen, laundry and housekeeping
- Functions: linen inventory; ward issue; collection; washing; processing; damaged and lost linen; distribution; cleaning schedules; ward, theatre, ICU, isolation cleaning; task assignment; inspections; incidents; supplies; audits.
- Tables: `linen_items`, `linen_movements`, `laundry_batches`, `cleaning_schedules`, `cleaning_tasks`, `cleaning_inspections`
- Roles: HSK, NUR, IPC
- Permissions: `laundry.{view,record}`, `housekeeping.task.{assign,complete}`, `housekeeping.inspection.record`
- Workflows: collect, then wash, then issue to ward. Cleaning task generated per schedule or after discharge or isolation, then inspected.
- Reports: linen loss, cleaning compliance.
- APIs: `POST /laundry/batches`, `POST /housekeeping/tasks`
- Depends on: M09, M32

#### M35 Kitchen and catering
- Functions: patient diet orders; meal plans; ward meals; special diets; kitchen inventory; meal production; distribution; nutrition integration; food wastage.
- Tables: `diet_orders_kitchen`, `meal_plans`, `meal_production`, `meal_distribution`, `kitchen_stock`, `food_wastage`
- Roles: KIT, NUT, NUR
- Permissions: `kitchen.{view,record}`, `kitchen.production.plan`, `kitchen.stock.manage`
- Workflows: diet orders per ward each cycle, then production list, then distribution, then wastage log.
- Reports: meals served, wastage, stock use.
- APIs: `GET /kitchen/production-list`
- Depends on: M09, M26

#### M36 Security and visitor management
- Functions: security officers; visitor management; staff access; patient visitor records; incident reports; lost and found; access control; CCTV integration; emergency alerts; gate passes; vehicle access.
- Tables: `visitors`, `visitor_passes`, `gate_passes`, `security_incidents`, `lost_found_items`, `access_events`
- Roles: SEC, ADM
- Permissions: `security.visitor.{register,view}`, `security.incident.{create,view}`, `security.gatepass.issue`
- Workflows: visitor check-in tied to patient and ward hours, then pass, then check-out. Incident report to escalation.
- Reports: visitor log, incidents.
- APIs: `POST /security/visitors`, `POST /security/incidents`
- Depends on: M09

#### M37 Fire, safety and occupational health
- Functions: incident reporting; fire inspections and equipment; safety inspections; occupational injuries; staff exposure; needle-stick injuries; PPE; risk assessments; drills; compliance reports.
- Tables: `safety_incidents`, `fire_equipment`, `fire_inspections`, `occupational_injuries`, `exposure_records`, `emergency_drills`, `risk_assessments`
- Roles: IPC, ADM, HRM
- Permissions: `ohs.incident.{create,view}`, `ohs.exposure.record`, `ohs.inspection.record`
- Workflows: needle-stick report, then PEP assessment (M18), then follow-up schedule. Fire equipment inspection due alerts.
- Reports: injuries, exposures, inspection compliance.
- APIs: `POST /ohs/exposures`
- Depends on: M43, M18

### Domain H: business and records

#### M38 Medical records and health information
- Functions: patient records; file management and tracking; MRN; document management and scanning; ICD clinical coding; discharge summaries; birth and death records; statistics; data quality; record requests; archiving; KHIS/DHIS2 reporting.
- Tables: `record_files`, `file_movements`, `record_requests`, `scanned_documents`, `coding_records`, `birth_notifications`, `death_notifications`, `report_submissions`, `data_quality_issues`
- Roles: MRO, DOC, ADM
- Permissions: `records.{view,request,release}`, `records.coding.{create,approve}`, `records.file.track`, `records.report.submit`
- Workflows: discharge, then coding, then summary complete, then archive. Record release request, then approval, then release with log. Monthly returns compiled and submitted.
- Reports: coding backlog, MOH returns, birth and death registers, data completeness.
- APIs: `POST /records/coding`, `POST /records/requests`, `POST /reports/khis/submit`
- Depends on: M09, M51

#### M39 Billing and cash office
- Functions: patient billing; service charges; invoices; payments (cash, card, M-Pesa, insurance, credit); refunds; discounts; waivers; receipts; payment allocation; outstanding balances; daily collections; cashier reconciliation.
- Tables: `charges`, `invoices`, `invoice_items`, `payments`, `payment_allocations`, `receipts`, `refunds`, `discounts`, `waivers`, `cashier_sessions`, `deposits`, `mpesa_transactions`, `credit_accounts`
- Roles: CSH, ACC, REC, MGT
- Permissions: `billing.invoice.{view,create,void}`, `billing.payment.{take,refund}`, `billing.discount.{apply,approve}`, `billing.waiver.approve`, `billing.cashier.{open,close,reconcile}`
- Workflows: every department posts charges to the patient account, then invoice generated, then payment or claim split, then receipt, then clearance at discharge. M-Pesa callbacks reconcile automatically.
- Reports: daily collections, outstanding bills, revenue by department, refunds, cashier variance, payer mix.
- APIs: `POST /billing/charges`, `POST /billing/invoices/{id}/pay`, `POST /billing/mpesa/stk`, `POST /billing/mpesa/callback`
- Depends on: all charge-posting modules, M40, M54

#### M40 SHA and insurance
- Functions: SHA patient verification and eligibility; membership; preauthorization; claims; claim batches; submission and tracking; rejections and resubmission; reconciliation; insurance companies; corporate clients; NHIF legacy records; tariffs; benefit packages.
- Tables: `payers`, `payer_plans`, `member_verifications`, `preauthorizations`, `claims`, `claim_items`, `claim_batches`, `claim_rejections`, `claim_remittances`, `tariffs`, `benefit_packages`, `legacy_nhif_records`
- Roles: CLM, CSH, REC, ACC
- Permissions: `insurance.verify`, `insurance.preauth.{request,view}`, `insurance.claim.{create,submit,resubmit}`, `insurance.batch.manage`, `insurance.remittance.reconcile`, `insurance.tariff.manage`
- Workflows: verify at registration, then preauth if required, then services tagged to payer, then claim built from invoice, then batch, then submit, then track, then rejection loop, then remittance reconciled to receivables.
- Reports: claims by status, rejection reasons, aging, remittance vs invoiced, benefit utilization.
- APIs: `POST /insurance/verify`, `POST /insurance/preauths`, `POST /insurance/claims/{id}/submit`, `POST /insurance/batches`
- Depends on: M01, M39, M42, M54

#### M41 Procurement and central stores
- Functions: suppliers; products; requisitions; approvals; RFQs; quotations; purchase orders; goods received; supplier invoices; store management (general, medical supplies, PPE, consumables, stationery); receiving; issuing; stock cards; batch tracking; expiry; min/max levels; stock count; adjustment; transfers.
- Tables: `suppliers`, `products`, `requisitions`, `requisition_approvals`, `rfqs`, `quotations`, `purchase_orders`, `goods_received_notes`, `supplier_invoices`, `stores`, `stock_items`, `stock_ledger`, `stock_counts`, `stock_adjustments`, `stock_transfers`, `stock_issues`
- Roles: PRO, STO, ACC, MGT
- Permissions: `procure.requisition.{create,approve}`, `procure.rfq.manage`, `procure.po.{create,approve}`, `stores.grn.receive`, `stores.issue.{create,approve}`, `stores.count.record`, `stores.adjust.approve`
- Workflows: requisition, then approval chain, then RFQ, then PO, then GRN, then invoice matching (three-way), then payable. Issues decrement stock ledger; low stock triggers reorder.
- Reports: stock valuation, reorder list, expiry, supplier performance, spend by department.
- APIs: `POST /procurement/requisitions`, `POST /procurement/po/{id}/receive`, `POST /stores/issues`
- Depends on: M42, M17

#### M42 Finance and accounting
- Functions: general ledger; chart of accounts; revenue; expenses; payments; receivables; payables; cashier; bank reconciliation; budgets; purchase accounting; supplier accounts; financial reports; profit and loss; balance sheet; audit trail.
- Tables: `gl_accounts`, `journal_entries`, `journal_lines`, `fiscal_periods`, `receivables`, `payables`, `bank_accounts`, `bank_reconciliations`, `budgets`, `budget_lines`, `expenses`
- Roles: ACC, AUD, MGT
- Permissions: `finance.journal.{create,post,reverse}`, `finance.period.close`, `finance.budget.manage`, `finance.bank.reconcile`, `finance.report.view`
- Workflows: sub-ledgers (billing, insurance, procurement, payroll) post journals automatically, then period close, then reports. Posted entries are immutable; corrections by reversal.
- Reports: trial balance, P&L, balance sheet, aged receivables and payables, budget vs actual, cash flow.
- APIs: `POST /finance/journals`, `POST /finance/periods/{id}/close`
- Depends on: M39, M40, M41, M43

### Domain I: people

#### M43 Human resources
- Functions: employees; departments; positions; qualifications; contracts; attendance; leave; shifts; rosters; payroll integration; performance; training; licences and professional registration; disciplinary records; staff documents.
- Tables: `employees`, `positions`, `qualifications`, `contracts`, `attendance_logs`, `leave_requests`, `leave_balances`, `shifts`, `rosters`, `payroll_exports`, `appraisals`, `trainings`, `licences`, `disciplinary_records`, `staff_documents`
- Roles: HRM, MGT, NUR
- Permissions: `hr.employee.{view,create,update}`, `hr.leave.{request,approve}`, `hr.roster.manage`, `hr.payroll.export`, `hr.disciplinary.view` (restricted)
- Workflows: hire, then contract, then roster, then attendance, then leave, then payroll export. Licence expiry alerts.
- Reports: headcount, attendance, leave, licence expiry, turnover.
- APIs: `POST /hr/employees`, `POST /hr/leave-requests`, `POST /hr/payroll/export`
- Depends on: M54

#### M44 Medical staff credentialing
- Functions: doctors and specialists; qualifications; licences and registration; specialties; privileges; theatre privileges; on-call schedules; contracts; credential expiry; CME/training.
- Tables: `practitioners`, `practitioner_qualifications`, `practitioner_licences`, `privileges`, `practitioner_privileges`, `oncall_schedules`, `cme_records`
- Roles: HRM, MGT, SPC
- Permissions: `credential.{view,manage}`, `credential.privilege.grant`, `oncall.manage`
- Workflows: credential verification, then privileges granted, then expiry alerts, then privilege suspension. Theatre booking checks surgeon privileges.
- Reports: credential expiry, on-call coverage, CME compliance.
- APIs: `POST /practitioners/{id}/privileges`, `GET /oncall`
- Depends on: M43, M11

### Domain J: access and digital

#### M45 Appointments and queue engine
- Functions: appointments; doctor, clinic, theatre, imaging schedules; lab, pharmacy, triage, consultation queues; queue numbers; SMS notifications; missed appointments; rescheduling; cancellation; waiting-time reports.
- Tables: `schedules`, `schedule_slots`, `appointments`, `queues`, `queue_tickets`, `queue_events`, `appointment_reminders`
- Roles: REC, NUR, DOC, PAT
- Permissions: `appointment.{view,create,cancel,reschedule}`, `schedule.manage`, `queue.{view,call,skip}`
- Workflows: book, then remind, then check-in creates queue ticket, then call, then serve. No-show marking and rebooking. One engine serves all departments.
- Reports: waiting time, no-show rate, slot utilization.
- APIs: `POST /appointments`, `GET /schedules/{id}/slots`, `POST /queues/{id}/call-next`
- Depends on: M01, M48

#### M46 Referral management
- Functions: incoming and outgoing referrals; referral letters; referring facility; receiving department; specialist referral; status; acceptance; transfer; ambulance; clinical handover; feedback; analytics.
- Tables: `referrals`, `referral_documents`, `referral_status_history`, `referring_facilities`, `referral_feedback`
- Roles: DOC, SPC, REC, DRV
- Permissions: `referral.{view,create,update}`, `referral.accept`, `referral.reject`, `referral.feedback.send`
- Workflows: incoming referral received, then triage, then accepted or rejected with reason, then patient arrives and links to registration, then treatment, then feedback to referring facility. Outgoing follows the reverse path, with transport.
- Reports: referrals in and out by facility, acceptance rate, turnaround, feedback completion.
- APIs: `POST /referrals`, `POST /referrals/{id}/accept`, `POST /referrals/{id}/feedback`
- Depends on: M01, M04, M30

#### M47 Telemedicine and patient portal
- Functions: virtual appointments; video consultation; specialist tele-consult; remote referrals; digital prescriptions; consultation notes; payment; portal registration; results, prescriptions, bills, records, discharge summaries; messaging; dependants.
- Tables: `tele_sessions`, `tele_participants`, `portal_accounts`, `portal_dependants`, `portal_messages`, `portal_access_logs`
- Roles: PAT, DOC, SPC
- Permissions: `tele.session.{create,join,close}`, `portal.self.view`, `portal.dependant.manage`
- Workflows: book, then pay, then join video session, then notes and e-prescription, then follow-up. Portal shows only released results.
- Reports: sessions, portal adoption, message response time.
- APIs: `POST /tele/sessions`, `POST /portal/register`, `GET /portal/me/results`
- Depends on: M45, M39, M03

#### M48 Patient communication
- Functions: SMS; email; WhatsApp where available; appointment reminders; payment, results, prescription notifications; health campaigns; emergency notifications; template management.
- Tables: `message_templates`, `outbound_messages`, `message_delivery_status`, `campaigns`, `opt_outs`
- Roles: ADM, CHW, ICT
- Permissions: `comms.template.manage`, `comms.campaign.{create,send}`, `comms.message.view`
- Workflows: event triggers message, then template rendered, then queued, then provider send, then delivery status callback, then retry on failure. Opt-out respected.
- Reports: delivery rate, cost, campaign reach.
- APIs: `POST /comms/send`, `POST /comms/callbacks/{provider}`
- Depends on: M54

### Domain K: management and system

#### M49 Research and training
- Functions: students; interns; residents; attachments; training programmes; supervisors; rotations; assessments; research projects; ethics approvals; clinical studies; publications; CME; attendance; certificates.
- Tables: `trainees`, `training_programs`, `rotations`, `supervisors`, `trainee_assessments`, `research_projects`, `ethics_approvals`, `publications`, `certificates`
- Roles: RSC, SPC, HRM
- Permissions: `training.{view,manage}`, `training.assessment.record`, `research.project.{view,manage}`, `research.ethics.record`
- Workflows: intake, then rotation allocation, then assessment, then certificate. Research project, then ethics approval, then study tracking.
- Reports: trainees by department, rotation coverage, projects, publications.
- APIs: `POST /training/rotations`, `POST /research/projects`
- Depends on: M43, M44

#### M50 Quality assurance, feedback and complaints
- Functions: quality indicators; patient satisfaction surveys; complaints, compliments, suggestions; incident reporting; clinical audits; mortality and morbidity reviews; waiting times; service and department KPIs; corrective actions; QI projects; investigation, response, escalation, resolution.
- Tables: `quality_indicators`, `indicator_values`, `surveys`, `survey_responses`, `complaints`, `complaint_actions`, `incidents`, `clinical_audits`, `mortality_reviews`, `corrective_actions`, `qi_projects`
- Roles: QAO, MGT, AUD
- Permissions: `quality.{view,manage}`, `complaint.{create,investigate,resolve}`, `incident.{report,review}`, `mmreview.record`
- Workflows: complaint logged, then assigned, then investigated, then response, then resolved or escalated. Incident, then review, then corrective action, then follow-up. Deaths queue for mortality review.
- Reports: complaint turnaround, satisfaction, incident trends, KPI by department.
- APIs: `POST /quality/complaints`, `POST /quality/incidents`
- Depends on: M09, M39

#### M51 Audit and compliance
- Functions: audit logs; user activity; patient record access logs; financial audit; clinical audit; incident management; compliance checklists; regulatory reports; data protection; access reviews.
- Tables: `audit_logs`, `access_logs`, `break_glass_events`, `compliance_checklists`, `compliance_items`, `data_subject_requests`, `access_reviews`, `consent_records`
- Roles: AUD, ADM, MGT
- Permissions: `audit.log.view`, `audit.access.view`, `compliance.checklist.manage`, `compliance.dsr.handle`, `access.review.perform`
- Workflows: every read and write logged. Break-glass access requires reason and is reviewed. Periodic access review. Data subject requests tracked to closure.
- Reports: access to sensitive records, failed logins, break-glass events, checklist status.
- APIs: `GET /audit/logs`, `POST /audit/access-reviews`
- Depends on: M54

#### M52 ICT department
- Functions: users; devices; computers; printers; network devices; IP addresses; software and licences; IT tickets; backups; system monitoring; user accounts; access control; cybersecurity incidents; system logs.
- Tables: `it_assets`, `network_devices`, `ip_allocations`, `software_licences`, `it_tickets`, `backup_jobs`, `backup_runs`, `security_incidents_it`, `system_health_checks`
- Roles: ICT, ADM
- Permissions: `ict.asset.{view,manage}`, `ict.ticket.{create,assign,close}`, `ict.backup.{view,run,restore}`
- Workflows: ticket raised, then assigned, then resolved. Backup scheduled, then verified, then restore tested.
- Reports: ticket SLA, backup success, licence expiry, asset inventory.
- APIs: `POST /ict/tickets`, `GET /ict/backups`, `GET /health`
- Depends on: M54

#### M53 Management dashboard and analytics
- Functions: executive dashboard showing OPD patients today, emergency patients, admissions, discharges, occupancy, available beds, ICU occupancy, theatre utilization, deliveries, lab and radiology workload, pharmacy sales, revenue, outstanding bills, SHA and insurance claims, cash collections, expenses, mortality, average waiting time, average length of stay, staff attendance, maintenance issues, critical equipment downtime. Role-based dashboards per department.
- Tables: `dashboard_definitions`, `kpi_definitions`, `kpi_snapshots`, `saved_reports`, `report_schedules` (or materialized views)
- Roles: MGT, ADM, dept heads
- Permissions: `dashboard.view`, `dashboard.department.view`, `report.custom.{create,run}`, `report.schedule.manage`
- Workflows: KPIs computed from source modules on a schedule or query, then displayed. Scheduled reports emailed.
- Reports: all module reports roll up here; custom report builder.
- APIs: `GET /dashboard/executive`, `GET /kpis/{code}`, `POST /reports/run`
- Depends on: all modules

#### M54 Master configuration, users, roles, integrations
- Functions: hospital profile; branches; departments; wards, rooms, beds; clinics; services; staff; roles and permissions; billing items and prices; insurance tariffs; SHA configuration; taxes; payment methods; SMS, email, M-Pesa settings; lab tests; radiology services; drug catalogue; appointment and queue settings; numbering; admission and discharge settings; notification, document, receipt, invoice templates; API keys and integrations; feature flags.
- Tables: `facility_profile`, `settings`, `number_sequences`, `services`, `price_lists`, `price_items`, `tax_rates`, `payment_methods`, `document_templates`, `notification_templates`, `integration_configs`, `api_clients`, `feature_flags`, plus core `roles`, `permissions`, `role_permissions`, `user_roles`
- Roles: ADM
- Permissions: `config.{view,update}`, `role.manage`, `user.{create,update,disable,reset_password}`, `integration.manage`, `price.manage`
- Workflows: configuration changes are versioned and audited. Price changes have effective dates. New roles inherit no permissions by default.
- Reports: configuration change log, users and roles listing, permission matrix export.
- APIs: `GET/PUT /config/{key}`, `POST /users`, `PUT /roles/{id}/permissions`, `POST /integrations/{name}/test`
- Depends on: none (foundation)

---

### 3.60 Core patient journey (end-to-end test)

The chain below must run on one patient record. The audit traces each hop in code.

1. Arrival, then M01 registration
2. M40 SHA or insurance verification
3. M02 triage
4. M45 queue
5. M03 consultation, diagnosis
6. M14 laboratory and M15 radiology orders
7. Results returned; doctor reviews
8. Prescription, then M17 pharmacy dispensing
9. M39 billing, then payment or M40 SHA claim
10. Discharge, or admission to M09
11. Ward care; M05 specialist review; M11 theatre, M10 ICU/HDU if required
12. Treatment; discharge; discharge summary (M38)
13. M45 follow-up appointment
14. M47 patient portal shows results, prescriptions, bills, summary
15. M53 reports and analytics update

Pass criteria: no hop creates a duplicate patient record; each hop writes `patient_id` and `visit_id`; each chargeable step posts to the same patient account; each step writes to `audit_logs`.

### 3.61 Suggested code architecture (engines, not 54 silos)

- Core clinical: registration, EMR, triage, consultation, inpatient, nursing, emergency, theatre, ICU/HDU, maternity, paediatrics, specialist clinics
- Diagnostics: laboratory, radiology, pathology, blood bank
- Medication: pharmacy, drug inventory, medication administration
- Public health: HTS, HIV care, TB, immunization, nutrition, family planning, surveillance
- Business: billing, payments, SHA, insurance, accounting, procurement, inventory
- Operations: maintenance, biomedical, laundry, housekeeping, kitchen, security, ambulance, mortuary
- People: HR, payroll integration, staff, nursing, doctors, rosters, training
- Management: reports, analytics, quality, audit, compliance, research
- Digital: patient portal, telemedicine, SMS, email, mobile/API, notifications
- System: users, roles, permissions, settings, activity logs, backups, API, integrations

Shared engines to build once and reuse: orders and results, queue and appointments, charges and billing, stock ledger, documents and templates, notifications, audit log.

---

## 4. Findings

The audit fills this section. Do not edit sections 1 to 3.

### 4.0 Stack and inventory

**Stack:**
- Language: PHP 8.2+ (composer.json:9)
- Framework: Laravel 12 (composer.json:10)
- ORM: Eloquent (default Laravel)
- Database: SQLite (active in .env:23), MySQL configured as fallback (.env.example:24-28)
- Frontend: Blade templates + Tailwind CSS 3 + Alpine.js 3, bundled with Vite 7 (package.json)
- Auth: Session-based (web guard), Laravel Sanctum for API tokens (composer.json:11)
- RBAC: Custom pivot tables (role_user, permission_role) + Spatie Laravel Permission installed but schema diverges (migration 2025_10_20_000000, 2025_10_20_173000)
- Queue: Database driver (QUEUE_CONNECTION=database in .env:38); Horizon NOT installed
- Audit: Spatie Laravel ActivityLog installed (composer.json:18) + custom audit_logs table (migration 2025_10_20_019600)
- Tests: PHPUnit 11.5 + Laravel Breeze scaffolding; 13 test files total
- PDF: DomPDF (composer.json:10)
- Excel: Maatwebsite Excel (composer.json:12)
- SMS: Twilio SDK (composer.json:21)

**Multi-tenancy model:** Single facility with multi-branch support via `hospital_branches` table (migration 2025_10_20_019400). No facility_id on most clinical tables; branch assignment is through employees/doctors, not enforced at query level on patient data.

**DB Tables (176 created, ~30 altered):**

Core/Auth: users, password_reset_tokens, sessions, cache, cache_locks, jobs, job_batches, failed_jobs, roles, permissions, role_user, permission_role, model_has_roles, model_has_permissions, role_has_permissions, personal_access_tokens

Patient/Clinical: patients, medical_histories, patient_allergies, patient_insurance, patient_portal_accounts, triages, vitals, opd_visits, ipd_admissions, bed_types, beds, bed_assignments, wards, appointments, appointment_requests, prescriptions, prescription_items, patient_diagnoses, diagnosis_categories, referrals, nursing_care_plans, consent_forms, documents, document_types

Diagnostics: lab_categories, lab_tests, lab_requests, lab_request_items, lab_equipment, lab_technicians, equipment_results, radiology_categories, radiology_tests, radiology_requests

Pharmacy: medicines, medicine_categories, medicine_brands, medicine_batches, drug_interactions, stores, store_stock, stock_movements, stock_adjustments, stocktakes, stocktake_items

Blood Bank: blood_groups, blood_donors, blood_inventory, blood_requests

Billing/Finance: invoices, invoice_items, payments, advance_payments, packages, package_items, mpesa_transactions, accounts, incomes, expenses, expense_categories, insurance_providers, insurance_claims, insurance_api_logs

SHA: sha_providers, sha_members, sha_authorizations, sha_service_codes, icd10_codes

HR: employees, employee_departments, designations, payrolls, schedules, attendances, leave_requests, leave_types, leave_balances, shifts, employee_shifts, public_holidays, performance_appraisals, training_programs, training_enrollments, hr_announcements

Recruitment: job_categories, job_postings, job_applications

Surgical/OT: ot_rooms, ot_schedules, ot_instrument_trays, ot_time_logs, operation_reports, birth_reports, death_reports

CSSD: cssd_instruments, cssd_batches

MRD: mrd_files, mrd_file_movements

Vaccination: vaccines, vaccination_records

Mortuary: mortuary_records, mortuary_releases

Equipment: medical_equipment, maintenance_logs

Ambulance: ambulances, ambulance_calls, emergency_admissions

Telemedicine: telemedicine_sessions

AI: ai_appointment_suggestions, ai_diagnosis_suggestions

Security: biometric_data, biometric_verification_logs, card_scan_logs, rfid_tags, iot_bed_sensors

Comms: notifications, message_templates, voice_notes

CMS: blog_categories, blog_posts, gallery_categories, gallery_items, job_categories, testimonials, notices, enquiries

Marketing: marketing_campaigns, marketing_posts, social_accounts, scheduled_posts, comment_replies, graphic_assets, marketing_analytics, seo_records

System: system_settings, audit_logs, modules, currencies, api_tokens, queue_management, visitor_logs, report_templates, e_prescription_templates, doctors, doctor_departments, nurses, nurse_departments, receptionists, pharmacists, lab_technicians, accountants, case_handlers, patient_cases, hospital_branches

**API Routes:**
- Web routes: ~200+ (routes/web.php, 600+ lines)
- API routes (routes/api.php): /api/v1/patients, /api/v1/doctors, /api/v1/appointments, /api/v1/invoices, /api/v1/payments, /api/v1/beds, /api/v1/tokens, /api/v1/login, /api/v1/register
- M-Pesa webhooks: /api/mpesa/callback, /api/mpesa/result, /api/mpesa/confirmation, /api/mpesa/validation

**Permissions seeded (93):** Patient Management (8), Appointments (6), Prescriptions (8), Lab & Radiology (7), Billing (9), IPD/OPD (7), Doctors & Staff (9), Bed Management (4), Accounting (5), Reports (9), Settings (4), CMS (8), Multi-Hospital (3), AI (6), System Admin (6), Marketing (12)

**Roles seeded (21):** Super Admin, Hospital Admin, Doctor, Nurse, Receptionist, Pharmacist, Lab Technician, Radiologist, Accountant, Case Handler, Ambulance Operator, HR Officer, Patient, System Auditor, Support Staff, Telemedicine Doctor, Inventory Manager, Procurement Officer, IT Support, Marketing Manager, System AI Bot

**Scheduled Jobs:** 3 artisan commands (approvals:check, eha:ping, mpesa:test-stk). No cron/kernel schedule defined. No Horizon.

**Queued Jobs (7):** BatchExport, CheckStockAlerts, GenerateIdCard, SendAppointmentReminders, SendPaymentReminders, GenerateDailyContent, PublishScheduledPost

**Notifications (9):** AppointmentReminder, ExportReadyNotification, InvoiceCreated, LabResultReadyNotification, LowStockAlert, PaymentReceived, PaymentReminder, PendingApprovalNotification, StockExpiryAlert

**Tests (13):** CrudCompleteTest, E2ETest, ExampleTest, ProfileTest, StoreInventoryTest, ApiTest, 6×Auth tests, ChartReadinessTest, PatientManagementTest

**Middleware (4):** CheckModule, PermissionMiddleware, RoleMiddleware, SetLocaleFromSession

**Views:** 14 subdirectories (admin, auth, cms, components, emails, errors, hms, integration, layouts, marketing, partials, patient-portal, pdf, profile, site)

**Integrations configured:** SHA/EHA (EHA_CLIENT_ID in .env), DHA Digital Health Superhighway, M-Pesa (sandbox), Twilio SMS (keys empty), Stripe/PayPal (sandbox)

### 4.1 Scoreboard

| ID | Module | Schema | Functions | Roles | Permissions | Workflows | Reports | APIs | UI | Tests | Overall % |
|---|---|---|---|---|---|---|---|---|---|---|---|
| M01 | Registration and MPI | PARTIAL | PARTIAL | PARTIAL | MISSING | PARTIAL | OK | PARTIAL | OK | PARTIAL | 35 |
| M02 | Triage | PARTIAL | PARTIAL | PARTIAL | MISSING | PARTIAL | PARTIAL | MISSING | OK | NONE | 30 |
| M03 | OPD / general consultation | PARTIAL | PARTIAL | PARTIAL | MISSING | OK | OK | MISSING | OK | NONE | 40 |
| M04 | Emergency and casualty | PARTIAL | PARTIAL | PARTIAL | MISSING | PARTIAL | PARTIAL | MISSING | OK | NONE | 25 |
| M05 | Specialist outpatient clinics | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M06 | Maternity, obstetrics and gynaecology | MISSING | MISSING | PARTIAL | MISSING | MISSING | PARTIAL | MISSING | MISSING | NONE | 5 |
| M07 | Neonatal unit | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M08 | Paediatrics | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M09 | Inpatient and wards | PARTIAL | PARTIAL | PARTIAL | PARTIAL | PARTIAL | PARTIAL | PARTIAL | OK | NONE | 40 |
| M10 | ICU and HDU | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M11 | Operating theatre | PARTIAL | PARTIAL | PARTIAL | PARTIAL | PARTIAL | PARTIAL | PARTIAL | OK | NONE | 35 |
| M12 | Anaesthesia | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M13 | Nursing management | PARTIAL | PARTIAL | PARTIAL | PARTIAL | PARTIAL | PARTIAL | MISSING | OK | NONE | 35 |
| M14 | Laboratory | PARTIAL | PARTIAL | PARTIAL | MISSING | MISSING | PARTIAL | MISSING | OK | NONE | 25 |
| M15 | Radiology and imaging | PARTIAL | PARTIAL | PARTIAL | MISSING | MISSING | PARTIAL | MISSING | OK | NONE | 25 |
| M16 | Blood bank | PARTIAL | PARTIAL | MISSING | MISSING | MISSING | PARTIAL | MISSING | OK | NONE | 15 |
| M17 | Pharmacy | PARTIAL | OK | OK | MISSING | PARTIAL | OK | MISSING | OK | NONE | 45 |
| M18 | HIV testing services and HIV care | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M19 | TB clinic | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M20 | Oncology | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M21 | Public health programmes | PARTIAL | PARTIAL | MISSING | MISSING | PARTIAL | PARTIAL | PARTIAL | OK | NONE | 20 |
| M22 | Dental and oral health | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M23 | Ophthalmology | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M24 | ENT and audiology | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M25 | Physiotherapy and occupational therapy | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M26 | Nutrition and dietetics | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M27 | Mental health | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M28 | Social work and patient welfare | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M29 | Mortuary | PARTIAL | PARTIAL | MISSING | MISSING | PARTIAL | PARTIAL | MISSING | OK | NONE | 20 |
| M30 | Ambulance and patient transport | PARTIAL | PARTIAL | PARTIAL | MISSING | PARTIAL | PARTIAL | MISSING | OK | NONE | 25 |
| M31 | CSSD | PARTIAL | PARTIAL | MISSING | MISSING | PARTIAL | PARTIAL | MISSING | OK | NONE | 20 |
| M32 | Infection prevention and control | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M33 | Maintenance, biomedical eng. and assets | PARTIAL | PARTIAL | MISSING | MISSING | PARTIAL | PARTIAL | MISSING | OK | NONE | 20 |
| M34 | Linen, laundry and housekeeping | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M35 | Kitchen and catering | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M36 | Security and visitor management | PARTIAL | PARTIAL | PARTIAL | MISSING | PARTIAL | PARTIAL | MISSING | OK | NONE | 25 |
| M37 | Fire, safety and occupational health | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M38 | Medical records and health information | PARTIAL | PARTIAL | MISSING | MISSING | PARTIAL | MISSING | MISSING | OK | NONE | 15 |
| M39 | Billing and cash office | PARTIAL | OK | PARTIAL | MISSING | PARTIAL | OK | PARTIAL | OK | NONE | 45 |
| M40 | SHA and insurance | PARTIAL | OK | PARTIAL | MISSING | OK | OK | PARTIAL | OK | NONE | 50 |
| M41 | Procurement and central stores | PARTIAL | OK | OK | MISSING | OK | OK | MISSING | OK | NONE | 55 |
| M42 | Finance and accounting | PARTIAL | OK | PARTIAL | MISSING | PARTIAL | OK | MISSING | OK | NONE | 40 |
| M43 | Human resources | PARTIAL | OK | PARTIAL | MISSING | OK | OK | MISSING | OK | NONE | 50 |
| M44 | Medical staff credentialing | PARTIAL | PARTIAL | MISSING | MISSING | PARTIAL | PARTIAL | MISSING | OK | NONE | 15 |
| M45 | Appointments and queue engine | PARTIAL | OK | OK | PARTIAL | PARTIAL | OK | PARTIAL | OK | NONE | 50 |
| M46 | Referral management | PARTIAL | OK | OK | MISSING | OK | PARTIAL | PARTIAL | OK | NONE | 45 |
| M47 | Telemedicine and patient portal | PARTIAL | OK | OK | PARTIAL | OK | PARTIAL | PARTIAL | OK | NONE | 45 |
| M48 | Patient communication | PARTIAL | PARTIAL | PARTIAL | MISSING | MISSING | MISSING | MISSING | PARTIAL | NONE | 15 |
| M49 | Research and training | PARTIAL | PARTIAL | PARTIAL | MISSING | PARTIAL | MISSING | PARTIAL | OK | NONE | 20 |
| M50 | Quality assurance, feedback and complaints | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 0 |
| M51 | Audit and compliance | PARTIAL | PARTIAL | OK | PARTIAL | PARTIAL | PARTIAL | MISSING | OK | NONE | 30 |
| M52 | ICT department | MISSING | MISSING | PARTIAL | MISSING | MISSING | MISSING | MISSING | MISSING | NONE | 5 |
| M53 | Management dashboard and analytics | PARTIAL | OK | OK | PARTIAL | PARTIAL | OK | PARTIAL | OK | NONE | 50 |
| M54 | Master configuration, users, roles, integrations | PARTIAL | OK | OK | OK | OK | OK | PARTIAL | OK | PARTIAL | 70 |

**Overall average: ~22%**

### 4.2 Cross-cutting checks

| Check | Status | Evidence | Notes |
|---|---|---|---|
| One patient record | PARTIAL | `patients` table is the sole MPI (migration 2025_10_20_010000); `patient_id` FK on opd_visits, ipd_admissions, prescriptions, lab_requests, radiology_requests, bed_assignments, referrals, nursing_care_plans, birth_reports, death_reports, insurance_claims, consent_forms, mrd_files, vaccination_records, mortuary_records, telemedicine_sessions, rfid_tags, emergency_admissions, blood_requests, patient_insurance, documents, voice_notes, ai_appointment_suggestions, ai_diagnosis_suggestions, patient_cases, sha_members, sha_authorizations, ot_schedules. **BUT:** No `facility_id` on clinical tables; `hospital_branches` exists but not enforced. Doctors/Nurses tables are separate from users (no unified staff record). `receptionists`, `pharmacists`, `lab_technicians`, `accountants` are separate tables, not linked to users/employees. |
| Patient journey (3.60) hop by hop | PARTIAL | 1. Arrival+M01: OK (PatientsController@store). 2. M40 SHA verify: OK (ShaController@verify). 3. M02 Triage: OK (TriageController@store). 4. M45 Queue: OK (QueueManagementController@store). 5. M03 Consult: PARTIAL (OpdVisitsController — no clinical notes). 6. M14/M15 orders: OK (LabRequestsController, RadiologyRequestsController). 7. Results: PARTIAL (LabRequestsController@processRequest — no verification step). 8. M17 Rx: OK (PharmacyController@dispensePrescription). 9. M39 Billing: OK (PaymentsController). 10. Discharge/M09: PARTIAL (IpdAdmissionsController — no discharge summary integration). 11. Ward care: PARTIAL (NursingCarePlanController — no MAR, no ward rounds). 12. Discharge/M38: PARTIAL (DischargeSummaryController exists but no coding). 13. M45 follow-up: OK (AppointmentsController). 14. M47 portal: OK (PatientPortalController). 15. M53 analytics: PARTIAL (BiDashboardController — no KPI snapshot). **BREAKS:** No clinical notes, no verification workflow, no coding, no MAR. |
| Server-side RBAC on all routes | MISSING | `PermissionMiddleware` exists at `app/Http/Middleware/PermissionMiddleware.php:9-28`. `RoleMiddleware` exists at `app/Http/Middleware/RoleMiddleware.php:9-28`. `CheckModule` exists at `app/Http/Middleware/CheckModule.php`. **BUT:** ZERO controllers use `->middleware('permission:...')` or `$this->authorize()`. All routes use only `auth` middleware (`web.php:108,312`). Any authenticated user can access any route. The only module-level check is `module:sha-shif` and `module:dha-integration` on SHA/DHA routes. |
| Audit log coverage | PARTIAL | Custom `AuditLog` model with `log()` static method (`app/Models/AuditLog.php:9-46`). Called from SettingsController for backup events (`SettingsController.php:211-219`). `Spatie\ActivityLog` installed (`composer.json:18`) but **NOT used** — custom model used instead. No audit logging on patient record create/update/delete, no read access logging, no IP capture. `audit_logs` table exists (migration 2025_10_20_019600) but sparsely populated. |
| Transactions and idempotency (money, stock) | PARTIAL | `PaymentsController@store` uses DB::transaction for payment+invoice update (`PaymentsController.php:163-176`). `PharmacyController@dispensePrescription` wraps stock decrement + charge in DB::transaction (`PharmacyController.php:134-190`). `MpesaCallbackController@handleCallback` is idempotent on `mpesa_transactions` unique constraint (`MpesaCallbackController.php:23-204`). `StocktakeController@adjust` uses transaction (`StocktakeController.php:157-229`). **MISSING:** No explicit idempotency keys on most endpoints. Refund/discount/waiver have no transaction guards. Lab/radiology charges not wrapped. |
| Integrations (SHA, M-Pesa, SMS, email, WhatsApp, KHIS/DHIS2, PACS/DICOM, analyzers) | PARTIAL | SHA/EHA: OK — `ShaService.php` with full verify→authorize→claim flow; EHA integration at `DhaIntegrationController`. M-Pesa: OK — STK push (`MpesaPaymentsController`), C2B callback (`MpesaCallbackController`), validation, confirmation. SMS: STUB — `SmsService.php` with Twilio/Africa's Talking/Nexmo adapters but keys empty in `.env`. Email: STUB — Mail configured as `log` driver in `.env`. WhatsApp: MISSING — no WhatsApp integration code. KHIS/DHIS2: MISSING — no reporting integration. PACS/DICOM: MISSING — `image_path` nullable on radiology_requests but no PACS client. Lab analyzers: STUB — `LabIntegrationController` exists with receiveResults endpoint but no HL7/ASTM parser. |
| Data protection and sensitive records | MISSING | No field-level encryption for HIV, mental health, or other sensitive data. `consent_forms` table exists (migration 2026_09_05_000021) with basic consent but no granular consent capture. No access logging for sensitive record reads. No data retention policies or purge logic. `biometric_data` table exists but stores templates as text (not encrypted). No break-glass access mechanism. No data subject request workflow. |
| Number sequences | PARTIAL | Patient MRN: auto-generated `PAT + padded count` in `Patient` model boot (`app/Models/Patient.php:27-31`). Admission number: auto-generated in `IpdAdmissionsController` (`IpdAdmissionsController.php:59`). Invoice number: auto-generated in `InvoicesController` (`InvoicesController.php:49`). Prescription number: auto-generated in `PrescriptionsController` (`PrescriptionsController.php:46`). Referral number: auto-generated `REF-IN/REF-OUT` in `Referral` model (`Referral.php:61-69`). **MISSING:** No `number_sequences` table for configurable sequences. Concurrency not tested — auto-increment may collide under high load. No visit number, lab order number, or receipt number sequences. |
| Background jobs and retry | PARTIAL | Database queue driver active (`QUEUE_CONNECTION=database`). 7 job classes: `BatchExport`, `CheckStockAlerts`, `GenerateIdCard`, `SendAppointmentReminders`, `SendPaymentReminders`, `GenerateDailyContent`, `PublishScheduledPost`. `failed_jobs` table exists. **MISSING:** No Horizon dashboard. No retry configuration. No job batching for complex workflows. No scheduled cron for periodic tasks (e.g., expiry alerts, report generation). `SendAppointmentReminders` and `SendPaymentReminders` exist but no scheduler triggers them. |
| Admin-editable configuration | PARTIAL | `SystemSetting` model with key-value store (`SystemSetting.php:8`). `SystemSettingsController` for timezone/theme/contact info (`SystemSettingsController.php:13-274`). `SettingsController` for branches/audit/backups (`SettingsController.php:18-343`). `ModulesController` for module enable/disable (`ModulesController.php:11-320`). `RoleManagementController` for roles/permissions (`RoleManagementController.php:12-204`). `IntegrationsController` for payment gateways/SMS (`IntegrationsController.php:11-69`). **MISSING:** No `facility_profile`, `number_sequences`, `services`, `price_lists`, `price_items`, `tax_rates`, `payment_methods`, `document_templates`, `notification_templates`, `integration_configs`, `api_clients`, or `feature_flags` tables. Prices are on `doctors` table (consultation_fee), not configurable per service. |
| Test coverage by module | MISSING | 13 test files total: `CrudCompleteTest`, `E2ETest`, `ExampleTest`, `ProfileTest`, `StoreInventoryTest`, `ApiTest`, 6×Auth tests, `ChartReadinessTest`, `PatientManagementTest`. No module-specific test coverage. No integration tests for billing, pharmacy, lab, or radiology workflows. No seed data tests. No RBAC tests. |

### 4.3 Gap register

| Gap ID | Module | Dimension | Description | Severity | Effort | Depends on |
|---|---|---|---|---|---|---|
| G001 | ALL | Permissions | Zero controllers enforce permissions server-side — `PermissionMiddleware` exists but no `middleware('permission:...')` applied to any route | P0 | M | M54 |
| G002 | M01 | Schema | Missing tables: patient_identifiers, patient_contacts, patient_biometrics (patient-based), duplicate_candidates, patient_merge_log, registration_types | P0 | L | — |
| G003 | M01 | Functions | No patient merge, duplicate detection, emergency registration, transfer-in, or next-of-kin functions | P0 | M | G002 |
| G004 | M01 | APIs | Missing: POST /patients/emergency, POST /patients/{id}/merge, POST /patients/{id}/biometrics/verify | P0 | S | G003 |
| G005 | M02 | Schema | Missing tables: triage_categories, triage_escalations | P1 | S | — |
| G006 | M02 | Functions | No pain score, GCS, pregnancy status, or escalation workflow | P1 | M | G005 |
| G007 | M02 | APIs | No triage API endpoints in routes/api.php | P1 | S | — |
| G008 | M03 | Schema | Missing tables: clinical_notes, medication_history, procedure_orders, sick_notes, medical_certificates | P1 | M | — |
| G009 | M03 | Functions | No differential diagnosis, clinical notes, procedure orders, sick notes, or medical certificates | P1 | M | G008 |
| G010 | M03 | APIs | No consultation API endpoints | P1 | S | — |
| G011 | M04 | Schema | Missing tables: resuscitation_records, trauma_assessments, emergency_procedures, observation_stays, emergency_dispositions | P0 | M | — |
| G012 | M04 | Functions | No resuscitation, trauma assessment, observation, or emergency billing deferral | P0 | M | G011 |
| G013 | M05 | ALL | Specialist outpatient clinics module entirely missing — no tables, controllers, models, views, or routes | P1 | L | M45 |
| G014 | M06 | ALL | Maternity module entirely missing — no ANC, pregnancy, labour, partograph, delivery, postnatal, family planning, gynaecology | P0 | L | M09 |
| G015 | M07 | ALL | Neonatal unit module entirely missing — no newborn registration, APGAR, NICU, incubator, phototherapy, feeding | P1 | L | M06 |
| G016 | M08 | ALL | Paediatrics module entirely missing — no growth charts, developmental assessment, weight-based dosing, child protection | P1 | L | M03, M09 |
| G017 | M09 | Schema | Missing tables: ward_rounds, nursing_notes, fluid_balance_entries, medication_administrations (MAR), care_plans (distinct), diet_orders, discharge_summaries, inpatient_transfers | P0 | L | — |
| G018 | M09 | Functions | No ward round recording, nursing notes, fluid balance, MAR, diet orders, or transfer workflow | P0 | L | G017 |
| G019 | M10 | ALL | ICU/HDU module entirely missing — no critical care charts, ventilator management, ABG, sedation, severity scores, infusions | P1 | L | M09 |
| G020 | M11 | Schema | Missing tables: surgical_waiting_list, preop_assessments, safety_checklists, theatre_teams, theatre_consumables, specimens, recovery_records | P1 | M | — |
| G021 | M11 | Functions | No WHO safety checklist, pre-op assessment workflow, theatre team assignment, consumable tracking, specimen management, recovery records | P1 | M | G020 |
| G022 | M12 | ALL | Anaesthesia module entirely missing — no assessments, records, drug tracking, intraop vitals, complications, post-review | P1 | L | M11 |
| G023 | M13 | Schema | Missing tables: nurse_allocations, duty_rosters, shift_handovers, nursing_workload_scores, nursing_procedures | P1 | M | — |
| G024 | M13 | Functions | No structured duty roster, shift handover, nurse allocation engine, workload scoring, or nursing procedures | P1 | M | G023 |
| G025 | M14 | Schema | Missing tables: lab_panels, lab_specimens, lab_worklists, lab_result_verifications, lab_critical_alerts, lab_qc_runs, lab_reagents, lab_reference_ranges, lab_analyzer_messages | P0 | L | — |
| G026 | M14 | Functions | No specimen tracking, worklists, verification workflow, pathologist approval, critical results alerts, QC, reagent tracking, turnaround time | P0 | L | G025 |
| G027 | M14 | APIs | No lab API endpoints | P1 | S | — |
| G028 | M15 | Schema | Missing tables: imaging_schedules, imaging_studies, imaging_report_versions, modality_worklist, contrast_records | P1 | M | — |
| G029 | M15 | Functions | No scheduling, modality worklist, PACS/DICOM integration, report versioning, radiologist approval workflow | P1 | M | G028 |
| G030 | M15 | APIs | No radiology API endpoints | P1 | S | — |
| G031 | M16 | Schema | Missing tables: blood_donations, blood_screening_results, crossmatch_requests, blood_issues, transfusions, transfusion_reactions, blood_wastage | P1 | L | — |
| G032 | M16 | Functions | No collection, screening, cross-matching, issue workflow, transfusion recording, reaction reporting, expiry management, traceability | P1 | L | G031 |
| G033 | M16 | APIs | No blood bank API endpoints | P1 | S | — |
| G034 | M17 | Schema | Missing tables: dispensations, dispensation_items, drug_returns, controlled_drug_register, drug_prices, reorder_levels | P1 | M | — |
| G035 | M17 | Functions | No drug returns, controlled drug register, GRN workflow, pharmacist verification step | P1 | M | G034 |
| G036 | M17 | APIs | No pharmacy API endpoints | P1 | S | — |
| G037 | M18 | ALL | HIV testing services and HIV care module entirely missing | P1 | L | M14, M17 |
| G038 | M19 | ALL | TB clinic module entirely missing | P1 | L | M14, M17, M18 |
| G039 | M20 | ALL | Oncology module entirely missing | P2 | L | M14, M15, M17 |
| G040 | M21 | Schema | Missing tables: immunization_schedules, immunization_doses, fp_visits, surveillance_cases, notifiable_disease_reports, outbreak_events | P1 | M | — |
| G041 | M21 | Functions | No family planning, immunization schedules, disease surveillance, outbreak management, health campaigns | P1 | M | G040 |
| G042 | M22-M28 | ALL | 7 allied/specialty modules entirely missing: dental, ophthalmology, ENT, physio/OT, nutrition, mental health, social work | P2 | XL | M03, M09 |
| G043 | M29 | Schema | Missing tables: mortuary_admissions, mortuary_slots, body_identifications, postmortems, release_authorizations, death_certificates | P1 | M | — |
| G044 | M29 | Functions | No postmortem recording, body identification, release authorization, death certificate issuance | P1 | M | G043 |
| G045 | M30 | Schema | Missing tables: drivers, crews, dispatches, trips, fuel_logs, vehicle_maintenance, handover_records | P1 | M | — |
| G046 | M30 | Functions | No trip tracking, handover workflow, fuel logs, vehicle maintenance, crew management | P1 | M | G045 |
| G047 | M31 | Schema | Missing tables: instrument_sets, cssd_cycles, sterilizer_runs, sterility_indicators, cssd_issues, cssd_returns | P1 | M | — |
| G048 | M31 | Functions | No sterilization cycle tracking, sterility indicators, issue/return tracking, instrument set management | P1 | M | G047 |
| G049 | M32 | ALL | Infection prevention and control module entirely missing | P1 | L | M14, M09 |
| G050 | M33 | Schema | Missing tables: maintenance_requests, work_orders, pm_schedules, calibrations, breakdowns, spare_parts, service_contracts, warranties, vendors, assets, asset_transfers, asset_disposals, asset_depreciation, asset_audits | P1 | L | — |
| G051 | M33 | Functions | No work orders, PM scheduling, calibration tracking, asset register, depreciation, vendor management | P1 | L | G050 |
| G052 | M34 | ALL | Linen, laundry and housekeeping module entirely missing | P2 | M | M09, M32 |
| G053 | M35 | ALL | Kitchen and catering module entirely missing | P2 | M | M09, M26 |
| G054 | M36 | Schema | Missing tables: visitor_passes, gate_passes, security_incidents, lost_found_items, access_events | P2 | M | — |
| G055 | M36 | Functions | No security incident management, gate pass, access control, lost and found | P2 | M | G054 |
| G056 | M37 | ALL | Fire, safety and occupational health module entirely missing | P2 | L | M43, M18 |
| G057 | M38 | Schema | Missing tables: record_requests, scanned_documents, coding_records, report_submissions, data_quality_issues | P1 | M | — |
| G058 | M38 | Functions | No ICD coding workflow, record request, data quality checks, KHIS/DHIS2 reporting, scanned documents | P1 | M | G057 |
| G059 | M39 | Schema | Missing tables: charges, payment_allocations, receipts, refunds, discounts, waivers, cashier_sessions, deposits, credit_accounts | P0 | L | — |
| G060 | M39 | Functions | No refund processing, discount/waiver approval, cashier session management, payment allocation, charges table | P0 | M | G059 |
| G061 | M39 | APIs | Missing: POST /billing/charges, POST /billing/invoices/{id}/pay | P1 | S | — |
| G062 | M40 | Schema | Missing tables: member_verifications, claim_items, claim_batches, claim_rejections, claim_remittances, tariffs, benefit_packages, legacy_nhif_records | P1 | M | — |
| G063 | M40 | Functions | No tariff CRUD, batch management, remittance reconciliation, claim rejection handling | P1 | M | G062 |
| G064 | M41 | Schema | Missing tables: products, rfqs, quotations, supplier_invoices, stock_issues | P1 | M | — |
| G065 | M41 | Functions | No RFQ management, GRN workflow, stock issues tracking | P1 | M | G064 |
| G066 | M41 | APIs | No procurement/store API endpoints | P1 | S | — |
| G067 | M42 | Schema | Missing tables: journal_entries, journal_lines, fiscal_periods, receivables, payables, bank_accounts, bank_reconciliations, budgets, budget_lines | P0 | L | — |
| G068 | M42 | Functions | No double-entry bookkeeping, journal entries, fiscal period close, bank reconciliation, budget management | P0 | L | G067 |
| G069 | M42 | APIs | No finance API endpoints | P1 | S | — |
| G070 | M43 | Schema | Missing tables: positions, qualifications, contracts, payroll_exports, licences, disciplinary_records, staff_documents | P1 | M | — |
| G071 | M43 | Functions | No disciplinary records, licence tracking, payroll export | P1 | S | G070 |
| G072 | M43 | APIs | No HR API endpoints | P1 | S | — |
| G073 | M44 | Schema | Missing tables: practitioner_qualifications, practitioner_licences, privileges, practitioner_privileges, oncall_schedules, cme_records | P1 | M | — |
| G074 | M44 | Functions | No credentialing, privilege granting, on-call scheduling, CME tracking, licence expiry alerts | P1 | M | G073 |
| G075 | M45 | Schema | Missing tables: schedule_slots, queues, queue_tickets, queue_events, appointment_reminders | P1 | M | — |
| G076 | M45 | Functions | No appointment reminders, queue events, schedule slot management | P1 | M | G075 |
| G077 | M46 | Schema | Missing tables: referral_documents, referral_status_history, referring_facilities, referral_feedback | P1 | M | — |
| G078 | M46 | Functions | No referral document management, status history, feedback workflow | P1 | S | G077 |
| G079 | M47 | Schema | Missing tables: tele_participants, portal_dependants, portal_messages, portal_access_logs | P2 | M | — |
| G080 | M47 | Functions | No portal dependants, portal messaging, access logging | P2 | M | G079 |
| G081 | M48 | Schema | Missing tables: outbound_messages, message_delivery_status, campaigns, opt_outs | P1 | M | — |
| G082 | M48 | Functions | No outbound message tracking, delivery status callbacks, campaign management, opt-out handling | P1 | M | G081 |
| G083 | M49 | Schema | Missing tables: trainees, rotations, supervisors, trainee_assessments, research_projects, ethics_approvals, publications, certificates | P2 | M | — |
| G084 | M49 | Functions | No research project management, ethics approvals, publications tracking | P2 | M | G083 |
| G085 | M50 | ALL | Quality assurance module entirely missing — no quality indicators, surveys, complaints, incidents, clinical audits, mortality reviews, corrective actions | P1 | L | M09 |
| G086 | M51 | Schema | Missing tables: access_logs, break_glass_events, compliance_checklists, compliance_items, data_subject_requests, access_reviews | P0 | M | — |
| G087 | M51 | Functions | No break-glass access, compliance checklists, DSR workflow, access reviews. Spatie ActivityLog installed but unused. | P0 | M | G086 |
| G088 | M52 | ALL | ICT department module entirely missing — no IT assets, tickets, backups management, network devices, software licences | P2 | L | M54 |
| G089 | M53 | Schema | Missing tables: dashboard_definitions, kpi_definitions, kpi_snapshots, saved_reports, report_schedules | P1 | M | — |
| G090 | M53 | Functions | No KPI snapshot workflow, scheduled reports, custom dashboard definitions | P1 | M | G089 |
| G091 | M54 | Schema | Missing tables: facility_profile, number_sequences, services, price_lists, price_items, tax_rates, payment_methods, document_templates, notification_templates, integration_configs, api_clients, feature_flags | P0 | L | — |
| G092 | M54 | Functions | No configurable numbering sequences, service catalog, price lists, tax rates, document/notification templates, integration configs, feature flags | P0 | L | G091 |
| G093 | ALL | APIs | Only 10 API endpoints exist (patients, doctors, appointments, invoices, payments, beds, tokens, login, register). All 54 modules lack REST APIs. | P0 | L | M54 |
| G094 | ALL | Tests | Only 13 test files exist. No module-specific tests, no integration tests, no RBAC tests, no workflow tests. | P1 | L | — |
| G095 | ALL | Schema | `created_by`/`updated_by` columns missing from most tables. `deleted_at` (soft delete) missing from most clinical tables. `facility_id` missing from all clinical tables. | P0 | L | M54 |
| G096 | M18-M20 | ALL | HIV, TB, Oncology programs entirely missing — required for Kenya Level 6 hospital reporting | P0 | XL | M14, M17 |
| G097 | M06-M08 | ALL | Maternity, Neonatal, Paediatrics entirely missing — core clinical departments | P0 | XL | M09 |
| G098 | ALL | Data Protection | No field-level encryption for sensitive records (HIV, mental health). No access logging. No data retention. No break-glass. | P0 | L | M51 |
| G099 | M39-M42 | Transactions | Billing, insurance, procurement lack consistent DB transaction wrapping and idempotency guards | P0 | M | — |
| G100 | ALL | Multi-tenancy | No facility_id enforcement on clinical tables. Branch isolation not enforced in queries. | P1 | L | M54 |

### 4.4 Suggested build order

**Phase A — Foundation (blocks everything)**
1. M54: Complete schema (facility_profile, number_sequences, services, price_lists, tax_rates, payment_methods, document_templates, notification_templates, integration_configs, feature_flags) — G091, G092
2. M54: Add created_by/updated_by, deleted_at, facility_id to all clinical tables — G095
3. ALL: Apply PermissionMiddleware to every controller route — G001
4. M51: Complete audit/compliance schema and functions (access_logs, break_glass, compliance checklists, DSR) — G086, G087
5. ALL: Add REST API layer for all modules — G093

**Phase B — Core patient journey (P0, must work end-to-end)**
6. M01: Complete Registration/MPI (patient_identifiers, patient_contacts, duplicate detection, merge, emergency registration) — G002, G003, G004
7. M39: Complete billing (charges, refunds, discounts, waivers, cashier sessions, payment allocations) — G059, G060, G061
8. M42: Implement double-entry bookkeeping (journal entries, fiscal periods, bank reconciliation, budgets) — G067, G068, G069
9. M09: Complete inpatient (ward rounds, nursing notes, fluid balance, MAR, diet orders, discharge summaries, transfers) — G017, G018
10. M14: Complete laboratory (specimens, worklists, verification, critical alerts, QC, reagent tracking) — G025, G026, G027

**Phase C — Clinical modules (P0, on patient journey)**
11. M02: Complete triage (triage_categories, escalations, pain score, GCS) — G005, G006, G007
12. M03: Complete consultation (clinical notes, medication history, procedure orders, sick notes, certificates) — G008, G009, G010
13. M04: Complete emergency (resuscitation, trauma, observation, billing deferral) — G011, G012
14. M15: Complete radiology (scheduling, worklist, PACS, report versioning, approval) — G028, G029, G030
15. M16: Complete blood bank (screening, crossmatch, issue, transfusion, reactions, traceability) — G031, G032, G033
16. M17: Complete pharmacy (controlled drugs, GRN, pharmacist verification, returns) — G034, G035, G036
17. M40: Complete SHA/insurance (tariffs, batches, remittance reconciliation, claim items) — G062, G063

**Phase D — Inpatient specialties (P0/P1)**
18. M06: Build maternity (ANC, pregnancy, labour, partograph, delivery, postnatal, family planning) — G014
19. M07: Build neonatal (newborn registration, APGAR, NICU, incubator, phototherapy) — G015
20. M08: Build paediatrics (growth charts, developmental assessment, weight-based dosing, child protection) — G016
21. M10: Build ICU/HDU (critical care charts, ventilator, ABG, sedation, severity scores) — G019
22. M11: Complete theatre (waiting list, pre-op, WHO checklist, teams, consumables, specimens, recovery) — G020, G021
23. M12: Build anaesthesia (assessments, records, drugs, intraop vitals, complications, post-review) — G022
24. M13: Complete nursing (duty roster, handover, allocation, workload, procedures) — G023, G024

**Phase E — Programmes and allied (P1)**
25. M18: Build HIV testing services and HIV care — G037
26. M19: Build TB clinic — G038
27. M21: Complete public health (immunization schedules, family planning, surveillance, outbreaks) — G040, G041
28. M27: Build mental health — G042
29. M28: Build social work — G042
30. M44: Complete credentialing (qualifications, licences, privileges, on-call, CME) — G073, G074

**Phase F — Operations (P1/P2)**
31. M29: Complete mortuary (postmortem, identification, release auth, death certificates) — G043, G044
32. M30: Complete ambulance (trips, handover, fuel, maintenance, crew) — G045, G046
33. M31: Complete CSSD (cycles, sterility indicators, issue/return, instrument sets) — G047, G048
34. M32: Build IPC (HAI cases, isolation, hand hygiene, outbreaks, antibiotic surveillance) — G049
35. M33: Complete maintenance (work orders, PM, calibration, assets, vendors) — G050, G051
36. M36: Complete security (incidents, gate passes, access control) — G054, G055

**Phase G — Business support (P1)**
37. M41: Complete procurement (RFQ, GRN, stock issues) — G064, G065, G066
38. M43: Complete HR (disciplinary, licences, payroll export) — G070, G071, G072
39. M38: Complete medical records (coding, record requests, KHIS, scanned docs) — G057, G058
40. M45: Complete appointments (schedule slots, reminders, queue events) — G075, G076
41. M46: Complete referrals (documents, status history, feedback) — G077, G078
42. M48: Complete communication (outbound tracking, delivery status, campaigns, opt-out) — G081, G082
43. M50: Build quality assurance (complaints, incidents, audits, mortality reviews, QI) — G085
44. M53: Complete dashboard (KPI snapshots, scheduled reports, custom dashboards) — G089, G090
45. M20: Build oncology — G039
46. M05: Build specialist outpatient clinics — G013

**Phase H — Lower priority (P2)**
47. M22-M26: Build dental, ophthalmology, ENT, physio/OT, nutrition — G042
48. M34-M35: Build linen/laundry/housekeeping, kitchen/catering — G052, G053
49. M37: Build fire, safety, occupational health — G056
50. M47: Complete telemedicine/portal (dependants, messaging, access logs) — G079, G080
51. M49: Complete research/training (research projects, ethics, publications) — G083, G084
52. M52: Build ICT department — G088

**Cross-cutting (parallel with all phases)**
- ALL: Comprehensive test suite — G094
- ALL: Data protection (encryption, access logging, retention) — G098
- ALL: Transaction consistency audit — G099
- ALL: Multi-tenancy enforcement — G100

### 4.5 Open questions

1. **Facility branding:** The `hospital_branches` table exists but there is no `facility_profile` table. What is the intended facility profile model? Single or multi-tenant?
2. **Doctor vs Employee:** Doctors and nurses are in separate tables from employees. Should these be unified? How does payroll work if doctors are not employees?
3. **Payer model:** The matrix expects `payers` and `payer_plans` but the code uses `insurance_providers` and `patient_insurance`. Is the current model sufficient or should it be generalized for NHIF/SHA/private payers?
4. **Spatie Permission usage:** The package is installed but a custom RBAC schema is used instead. Should the codebase migrate to Spatie's standard tables or keep the custom approach?
5. **Spatie ActivityLog usage:** Installed but unused. Should it replace the custom `AuditLog` model?
6. **Queue scheduling:** 7 job classes exist but no cron/kernel schedule triggers them. What is the intended scheduling mechanism?
7. **Multi-currency:** A `currencies` table and `CurrencyService` exist but billing uses a single currency. Is multi-currency a requirement?
8. **M-Pesa callback security:** The callback endpoint at `/api/mpesa/callback` has no authentication (only CSRF exemption). Is callback validation via Safaricom sufficient?
9. **Data retention:** What are the legal retention periods for patient records, audit logs, and financial records in Kenya?
10. **Interoperability:** DHA integration exists but KHIS/DHIS2 reporting is missing. What reporting format is required for MOH returns?
11. **Blood bank scope:** The current blood bank module covers donors, inventory, and requests. Does the facility collect blood (full blood bank) or only receive from national transfusion service?
12. **Maternity scope:** Kenya Level 6 hospitals handle high-risk pregnancies. Should the maternity module include high-risk scoring, maternal death audit, or confidential enquiry integration?
