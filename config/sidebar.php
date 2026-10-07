<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HMS Sidebar Navigation
    |--------------------------------------------------------------------------
    | Config-driven menu. Order is the display order.
    | Administration is intentionally last.
    |
    | Each section:
    |   key, label, icon, color, permission (any-of list), module (optional)
    |   items: nested children (max depth 2 for mobile)
    |
    | Each item:
    |   label, route (name), permission (optional), active (route pattern), external
    */

    'sections' => [

        // ---------------------------------------------------------------------
        // 1. DASHBOARD
        // ---------------------------------------------------------------------
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'fa-home',
            'color' => 'emerald',
            'permission' => ['view dashboard analytics', 'view analytics'],
            'items' => [
                ['label' => 'Overview', 'route' => 'dashboard', 'icon' => 'fa-compass', 'active' => 'dashboard'],
                ['label' => 'Analytics', 'route' => 'analytics.bi-dashboard', 'icon' => 'fa-chart-bar', 'permission' => ['view analytics'], 'active' => 'analytics.bi-dashboard*'],
                ['label' => 'Revenue Analytics', 'route' => 'analytics.revenue', 'icon' => 'fa-chart-line', 'permission' => ['view analytics'], 'active' => 'analytics.revenue*'],
                ['label' => 'Patient Analytics', 'route' => 'analytics.patients', 'icon' => 'fa-users', 'permission' => ['view analytics'], 'active' => 'analytics.patients*'],
                ['label' => 'Occupancy Analytics', 'route' => 'analytics.occupancy', 'icon' => 'fa-bed', 'permission' => ['view analytics'], 'active' => 'analytics.occupancy*'],
                ['label' => 'KPIs', 'route' => 'hms.dashboard.kpis.index', 'icon' => 'fa-gauge-high', 'active' => 'hms.dashboard.kpis.*'],
                ['label' => 'Definitions', 'route' => 'hms.dashboard.definitions.index', 'icon' => 'fa-book-open', 'active' => 'hms.dashboard.definitions.*'],
                ['label' => "Today's Summary", 'route' => 'hms.dashboard.today-summary', 'icon' => 'fa-calendar-day', 'active' => 'hms.dashboard.today-summary'],
                ['label' => 'Notifications', 'route' => 'hms.dashboard.notifications', 'icon' => 'fa-bell', 'active' => 'hms.dashboard.notifications'],
                ['label' => 'Active Staff', 'route' => 'hms.dashboard.active-staff', 'icon' => 'fa-users', 'active' => 'hms.dashboard.active-staff'],
                ['label' => 'Global Search', 'route' => 'hms.global-search', 'icon' => 'fa-search', 'active' => 'hms.global-search'],
            ],
        ],

        // ---------------------------------------------------------------------
        // 2. CLINICAL
        // ---------------------------------------------------------------------
        [
            'key' => 'clinical',
            'label' => 'Clinical',
            'icon' => 'fa-heartbeat',
            'color' => 'blue',
            'permission' => [
                'view patients', 'add patients', 'edit patients', 'view doctors', 'manage staff profiles',
                'manage nurses', 'manage ambulances', 'create appointments', 'manage appointments',
                'view appointments', 'manage queue', 'view prescriptions', 'manage case handlers',
                'generate operation reports', 'manage bed assignments', 'manage patient vitals',
                'admit patients', 'manage admissions',
            ],
            'items' => [
                [
                    'label' => 'Patients',
                    'icon' => 'fa-user-injured',
                    'permission' => ['view patients', 'add patients', 'edit patients'],
                    'children' => [
                        ['label' => 'All Patients', 'route' => 'hms.patients.index', 'permission' => ['view patients'], 'active' => 'hms.patients.*'],
                        ['label' => 'OPD Visits', 'route' => 'hms.opd.index', 'active' => 'hms.opd.*'],
                        ['label' => 'IPD Admissions', 'route' => 'hms.ipd.index', 'active' => 'hms.ipd.*'],
                        ['label' => 'ICU', 'route' => 'hms.icu.index', 'active' => 'hms.icu.*'],
                        ['label' => 'Triage', 'route' => 'hms.triage.index', 'active' => 'hms.triage.index*'],
                        ['label' => 'Triage Escalations', 'route' => 'hms.triage.escalations.index', 'active' => 'hms.triage.escalations.*'],
                        ['label' => 'Vitals', 'route' => 'hms.vitals.index', 'active' => 'hms.vitals.*'],
                        ['label' => 'Diagnosis', 'route' => 'hms.diagnosis.patient-diagnoses', 'active' => 'hms.diagnosis.patient-diagnoses*'],
                        ['label' => 'Diagnosis Categories', 'route' => 'hms.diagnosis.categories', 'active' => 'hms.diagnosis.categories*'],
                        ['label' => 'ICD-10 Codes', 'route' => 'hms.icd10.index', 'active' => 'hms.icd10.*'],
                        ['label' => 'Discharge Summary', 'route' => 'hms.discharge-summary.index', 'active' => 'hms.discharge-summary.*'],
                        ['label' => 'Medical History & Vitals', 'route' => 'hms.medical-history.index', 'active' => 'hms.medical-history.*'],
                        ['label' => 'Visitors', 'route' => 'hms.visitors.index', 'active' => 'hms.visitors.index*'],
                        ['label' => 'Visitor Analytics', 'route' => 'hms.visitors.analytics', 'active' => 'hms.visitors.analytics*'],
                    ],
                ],
                [
                    'label' => 'Specialty Care',
                    'icon' => 'fa-stethoscope',
                    'children' => [
                        ['label' => 'Vaccination', 'route' => 'vaccination.index', 'active' => 'vaccination.index*'],
                        ['label' => 'Administer Vaccine', 'route' => 'vaccination.administer', 'active' => 'vaccination.administer*'],
                        ['label' => 'Public Health Immunizations', 'route' => 'public-health.immunizations.schedule', 'active' => 'public-health.immunizations.*'],
                        ['label' => 'Family Planning', 'route' => 'public-health.fp.index', 'active' => 'public-health.fp.*'],
                        ['label' => 'Disease Surveillance', 'route' => 'public-health.surveillance.index', 'active' => 'public-health.surveillance.*'],
                        ['label' => 'Outbreaks', 'route' => 'public-health.outbreaks.index', 'active' => 'public-health.outbreaks.*'],
                        ['label' => 'Paediatrics Immunizations', 'route' => 'hms.paediatrics.immunizations.index', 'active' => 'hms.paediatrics.*'],
                        ['label' => 'Maternity / Pregnancies', 'route' => 'hms.maternity.pregnancies.index', 'active' => 'hms.maternity.*'],
                        ['label' => 'Neonatal / Newborns', 'route' => 'hms.neonatal.newborns.index', 'active' => 'hms.neonatal.*'],
                        ['label' => 'HIV HTS', 'route' => 'hms.hiv.hts.index', 'active' => 'hms.hiv.hts.*'],
                        ['label' => 'HIV Care', 'route' => 'hms.hiv.care.index', 'active' => 'hms.hiv.care.*'],
                        ['label' => 'TB Cases', 'route' => 'hms.tb.cases.index', 'active' => 'hms.tb.*'],
                        ['label' => 'Mental Health', 'route' => 'hms.mental-health.treatment-plans.index', 'active' => 'hms.mental-health.*'],
                        ['label' => 'Social Discharge Plans', 'route' => 'hms.social.discharge-plans.index', 'active' => 'hms.social.*'],
                        ['label' => 'Dental Records', 'route' => 'hms.dental.records.index', 'active' => 'hms.dental.*'],
                        ['label' => 'ENT Records', 'route' => 'hms.ent.records.index', 'active' => 'hms.ent.*'],
                        ['label' => 'Ophthalmology Exams', 'route' => 'hms.ophthalmology.exams.index', 'active' => 'hms.ophthalmology.*'],
                        ['label' => 'Rehab Sessions', 'route' => 'hms.rehab.sessions.index', 'active' => 'hms.rehab.*'],
                        ['label' => 'Nutrition Records', 'route' => 'hms.nutrition.records.index', 'active' => 'hms.nutrition.*'],
                        ['label' => 'Oncology Plans', 'route' => 'hms.oncology.plans.index', 'active' => 'hms.oncology.plans.*'],
                        ['label' => 'Oncology Registrations', 'route' => 'hms.oncology.registrations.index', 'active' => 'hms.oncology.registrations.*'],
                        ['label' => 'Oncology Protocols', 'route' => 'hms.oncology.protocols.index', 'active' => 'hms.oncology.protocols.*'],
                    ],
                ],
                [
                    'label' => 'Reception & Queue',
                    'icon' => 'fa-concierge-bell',
                    'permission' => ['create appointments', 'manage appointments', 'view appointments', 'manage queue'],
                    'children' => [
                        ['label' => 'Appointments', 'route' => 'hms.appointments.index', 'active' => 'hms.appointments.*'],
                        ['label' => 'Calendar', 'route' => 'hms.calendar.index', 'active' => 'hms.calendar.*'],
                        ['label' => 'Online Requests', 'route' => 'admin.appointments.requests', 'active' => 'admin.appointments.requests'],
                        ['label' => 'Queue Management', 'route' => 'hms.queue.index', 'active' => 'hms.queue.index*'],
                        ['label' => 'Token Generation', 'route' => 'hms.queue.token-generation', 'active' => 'hms.queue.token-generation*'],
                        ['label' => 'Display Board', 'route' => 'hms.queue.display-board', 'external' => true, 'active' => 'hms.queue.display-board*'],
                        ['label' => 'Kiosk Mode', 'route' => 'hms.queue.kiosk', 'external' => true, 'active' => 'hms.queue.kiosk*'],
                        ['label' => 'Smart Display', 'route' => 'hms.queue.smart-display', 'external' => true, 'active' => 'hms.queue.smart-display*', 'badge' => 'Pro'],
                        ['label' => 'Enquiries', 'route' => 'admin.enquiries.index', 'active' => 'admin.enquiries.*'],
                        ['label' => 'Feedback / Complaints', 'route' => 'hms.enquiries.feedback', 'active' => 'hms.enquiries.feedback*'],
                    ],
                ],
                [
                    'label' => 'Care Teams',
                    'icon' => 'fa-user-md',
                    'permission' => ['view doctors', 'manage staff profiles', 'manage nurses', 'manage ambulances', 'manage case handlers'],
                    'children' => [
                        ['label' => 'Doctors', 'route' => 'hms.doctors.index', 'permission' => ['view doctors'], 'active' => 'hms.doctors.index*'],
                        ['label' => 'Doctor Departments', 'route' => 'hms.doctors.departments.index', 'active' => 'hms.doctors.departments.*'],
                        ['label' => 'Doctor Charges', 'route' => 'hms.doctor-charges.index', 'active' => 'hms.doctor-charges.*'],
                        ['label' => 'Nurses', 'route' => 'hms.nurses.index', 'permission' => ['manage nurses'], 'active' => 'hms.nurses.index*'],
                        ['label' => 'Nurse Departments', 'route' => 'hms.nurses.departments', 'permission' => ['manage nurses'], 'active' => 'hms.nurses.departments*'],
                        ['label' => 'Nursing Roster', 'route' => 'hms.nurses.duty-roster', 'active' => 'hms.nurses.duty-roster*'],
                        ['label' => 'Nurse Ward Assign', 'route' => 'hms.nurses.assign-wards', 'active' => 'hms.nurses.assign-wards*'],
                        ['label' => 'Nursing Care Plans', 'route' => 'hms.nursing-care-plans.index', 'active' => 'hms.nursing-care-plans.*'],
                        ['label' => 'Nursing Allocations', 'route' => 'hms.nursing.allocations.index', 'active' => 'hms.nursing.allocations.*'],
                        ['label' => 'Case Handlers', 'route' => 'hms.case-handlers.index', 'permission' => ['manage case handlers'], 'active' => 'hms.case-handlers.index*'],
                        ['label' => 'Patient Cases', 'route' => 'hms.case-handlers.cases', 'permission' => ['manage case handlers'], 'active' => 'hms.case-handlers.cases*'],
                        ['label' => 'Emergency & Ambulance', 'route' => 'hms.ambulance.index', 'permission' => ['manage ambulances'], 'active' => 'hms.ambulance.index*'],
                        ['label' => 'Ambulance Calls', 'route' => 'hms.ambulance.calls', 'permission' => ['manage ambulances'], 'active' => 'hms.ambulance.calls*'],
                        ['label' => 'Ambulance Emergency', 'route' => 'hms.ambulance.emergency', 'permission' => ['manage ambulances'], 'active' => 'hms.ambulance.emergency*'],
                        ['label' => 'Ambulance Crews', 'route' => 'hms.ambulance.crews.index', 'permission' => ['manage ambulances'], 'active' => 'hms.ambulance.crews.*'],
                        ['label' => 'Ambulance Trips', 'route' => 'hms.ambulance.trips.index', 'permission' => ['manage ambulances'], 'active' => 'hms.ambulance.trips.*'],
                        ['label' => 'Ambulance Maintenance', 'route' => 'hms.ambulance.maintenance.index', 'permission' => ['manage ambulances'], 'active' => 'hms.ambulance.maintenance.*'],
                        ['label' => 'Lab Technicians', 'route' => 'hms.staff.lab-technicians', 'active' => 'hms.staff.lab-technicians*'],
                        ['label' => 'Reception Staff', 'route' => 'hms.staff.receptionists', 'active' => 'hms.staff.receptionists*'],
                        ['label' => 'Pharmacy Staff', 'route' => 'hms.staff.pharmacists', 'active' => 'hms.staff.pharmacists*'],
                        ['label' => 'Accounting Staff', 'route' => 'hms.staff.accountants', 'active' => 'hms.staff.accountants*'],
                    ],
                ],
                [
                    'label' => 'Procedures & Notes',
                    'icon' => 'fa-notes-medical',
                    'permission' => ['view prescriptions', 'generate operation reports', 'view patients'],
                    'children' => [
                        ['label' => 'Prescriptions', 'route' => 'hms.pharmacy.prescriptions.index', 'permission' => ['view prescriptions'], 'active' => 'hms.pharmacy.prescriptions.*'],
                        ['label' => 'E-Prescription', 'route' => 'hms.prescriptions.e-prescription.templates', 'permission' => ['view prescriptions'], 'active' => 'hms.prescriptions.e-prescription.templates*', 'badge' => 'Pro'],
                        ['label' => 'E-Prescriptions List', 'route' => 'hms.prescriptions.e-prescription.index', 'permission' => ['view prescriptions'], 'active' => 'hms.prescriptions.e-prescription.index*'],
                        ['label' => 'Manage E-Rx Templates', 'route' => 'hms.prescriptions.e-prescription.manage-templates', 'permission' => ['view prescriptions'], 'active' => 'hms.prescriptions.e-prescription.manage-templates*'],
                        ['label' => 'Theatre / OT Schedule', 'route' => 'hms.ot.index', 'active' => 'hms.ot.index*'],
                        ['label' => 'OT Schedule Board', 'route' => 'hms.ot.schedule', 'active' => 'hms.ot.schedule*'],
                        ['label' => 'OT Rooms', 'route' => 'hms.ot.rooms', 'active' => 'hms.ot.rooms*'],
                        ['label' => 'OT Instruments', 'route' => 'hms.ot.instruments', 'active' => 'hms.ot.instruments*'],
                        ['label' => 'Operations Reports', 'route' => 'hms.operations.index', 'permission' => ['generate operation reports'], 'active' => 'hms.operations.*'],
                        ['label' => 'Referrals', 'route' => 'hms.referrals.index', 'active' => 'hms.referrals.index*'],
                        ['label' => 'Referral Facilities', 'route' => 'hms.referrals.facilities.index', 'active' => 'hms.referrals.facilities.*'],
                        ['label' => 'Consent Forms', 'route' => 'consent.index', 'active' => 'consent.*'],
                        ['label' => 'Medical Records (MRD)', 'route' => 'mrd.index', 'active' => 'mrd.*'],
                    ],
                ],
                [
                    'label' => 'Capacity',
                    'icon' => 'fa-bed',
                    'permission' => ['manage bed assignments', 'view patients'],
                    'children' => [
                        ['label' => 'Wards', 'route' => 'hms.wards.index', 'active' => 'hms.wards.*'],
                        ['label' => 'Beds & Assignments', 'route' => 'hms.beds.index', 'permission' => ['manage bed assignments'], 'active' => 'hms.beds.*'],
                        ['label' => 'Bed Types', 'route' => 'hms.bed-types.index', 'permission' => ['manage bed assignments'], 'active' => 'hms.bed-types.*'],
                        ['label' => 'Bed Visualization', 'route' => 'iot.bed-occupancy-map', 'permission' => ['manage bed assignments', 'monitor iot sensors'], 'active' => 'iot.bed-occupancy-map*'],
                    ],
                ],
                [
                    'label' => 'Mortuary & CSSD',
                    'icon' => 'fa-box',
                    'children' => [
                        ['label' => 'Mortuary', 'route' => 'mortuary.index', 'active' => 'mortuary.*'],
                        ['label' => 'CSSD Overview', 'route' => 'cssd.index', 'active' => 'cssd.index*'],
                        ['label' => 'CSSD Instrument Sets', 'route' => 'hms.cssd.instrument-sets.index', 'active' => 'hms.cssd.instrument-sets.*'],
                    ],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // 3. DIAGNOSTICS
        // ---------------------------------------------------------------------
        [
            'key' => 'diagnostics',
            'label' => 'Diagnostics',
            'icon' => 'fa-microscope',
            'color' => 'rose',
            'permission' => [
                'manage test categories', 'add test requests', 'enter test results',
                'manage blood bank', 'view test results', 'manage lab worklists',
            ],
            'items' => [
                [
                    'label' => 'Laboratory',
                    'icon' => 'fa-flask',
                    'children' => [
                        ['label' => 'Lab Overview', 'route' => 'hms.laboratory.index', 'active' => 'hms.laboratory.index*'],
                        ['label' => 'Lab Tests', 'route' => 'hms.laboratory.tests.index', 'active' => 'hms.laboratory.tests.*'],
                        ['label' => 'Test Categories', 'route' => 'hms.test-categories.index', 'active' => 'hms.test-categories.*'],
                        ['label' => 'Lab Requests / Worklist', 'route' => 'hms.laboratory.requests.index', 'active' => 'hms.laboratory.requests.*'],
                        ['label' => 'Lab Reports', 'route' => 'hms.laboratory.reports', 'active' => 'hms.laboratory.reports*'],
                        ['label' => 'Lab Technicians', 'route' => 'hms.laboratory.technicians.index', 'active' => 'hms.laboratory.technicians.*'],
                    ],
                ],
                [
                    'label' => 'Radiology / Imaging',
                    'icon' => 'fa-x-ray',
                    'children' => [
                        ['label' => 'Radiology Overview', 'route' => 'hms.radiology.index', 'active' => 'hms.radiology.index*'],
                        ['label' => 'Radiology Tests', 'route' => 'hms.radiology.tests.index', 'active' => 'hms.radiology.tests.*'],
                        ['label' => 'Radiology Requests', 'route' => 'hms.radiology.requests.index', 'active' => 'hms.radiology.requests.*'],
                        ['label' => 'Modality Worklist', 'route' => 'hms.radiology.worklist.index', 'active' => 'hms.radiology.worklist.*'],
                    ],
                ],
                [
                    'label' => 'Other Investigations',
                    'icon' => 'fa-search-plus',
                    'children' => [
                        ['label' => 'Investigation Reports', 'route' => 'hms.investigation-reports.index', 'active' => 'hms.investigation-reports.*'],
                    ],
                ],
                [
                    'label' => 'Blood Bank',
                    'icon' => 'fa-tint',
                    'permission' => ['manage blood bank'],
                    'children' => [
                        ['label' => 'Blood Groups / Stock', 'route' => 'hms.bloodbank.index', 'active' => 'hms.bloodbank.index*'],
                        ['label' => 'Donors', 'route' => 'hms.bloodbank.donors', 'active' => 'hms.bloodbank.donors*'],
                        ['label' => 'Blood Requests', 'route' => 'hms.bloodbank.requests', 'active' => 'hms.bloodbank.requests*'],
                        ['label' => 'Stock Levels', 'route' => 'hms.bloodbank.stock-levels', 'active' => 'hms.bloodbank.stock-levels*'],
                    ],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // 4. PHARMACY & INVENTORY
        // ---------------------------------------------------------------------
        [
            'key' => 'pharmacy-inventory',
            'label' => 'Pharmacy & Inventory',
            'icon' => 'fa-pills',
            'color' => 'amber',
            'permission' => [
                'view prescriptions', 'dispense medicines', 'manage medicine inventory',
                'manage packages', 'manage inventory', 'manage suppliers',
            ],
            'items' => [
                [
                    'label' => 'Pharmacy',
                    'icon' => 'fa-prescription-bottle',
                    'children' => [
                        ['label' => 'Pharmacy Dashboard', 'route' => 'hms.pharmacy.index', 'active' => 'hms.pharmacy.index*'],
                        ['label' => 'Medicines', 'route' => 'hms.pharmacy.medicines.index', 'permission' => ['manage medicine inventory'], 'active' => 'hms.pharmacy.medicines.*'],
                        ['label' => 'Medicine Categories', 'route' => 'hms.pharmacy.medicine-categories.index', 'active' => 'hms.pharmacy.medicine-categories.*'],
                        ['label' => 'Medicine Brands', 'route' => 'hms.pharmacy.medicine-brands.index', 'active' => 'hms.pharmacy.medicine-brands.*'],
                        ['label' => 'Controlled Drugs', 'route' => 'hms.pharmacy.controlled-drugs.index', 'active' => 'hms.pharmacy.controlled-drugs.*'],
                        ['label' => 'Drug Interactions', 'route' => 'drug-interactions.index', 'active' => 'drug-interactions.*'],
                        ['label' => 'Prescriptions', 'route' => 'hms.pharmacy.prescriptions.index', 'active' => 'hms.pharmacy.prescriptions.index*'],
                    ],
                ],
                [
                    'label' => 'Inventory',
                    'icon' => 'fa-boxes-stacked',
                    'children' => [
                        ['label' => 'Inventory Dashboard', 'route' => 'hms.inventory.index', 'active' => 'hms.inventory.index*'],
                        ['label' => 'Item Categories', 'route' => 'hms.inventory.categories', 'active' => 'hms.inventory.categories*'],
                        ['label' => 'Stock Movements', 'route' => 'hms.inventory.stock-movements.index', 'active' => 'hms.inventory.stock-movements.*'],
                        ['label' => 'Expiry Alerts', 'route' => 'hms.inventory.expiry-alerts', 'active' => 'hms.inventory.expiry-alerts*'],
                        ['label' => 'Stock Report', 'route' => 'hms.inventory.stock-report', 'active' => 'hms.inventory.stock-report*'],
                        ['label' => 'Stock Take', 'route' => 'hms.inventory.stock-take', 'active' => 'hms.inventory.stock-take*'],
                        ['label' => 'Stock Adjustments', 'route' => 'hms.stock-adjustments.index', 'active' => 'hms.stock-adjustments.*'],
                        ['label' => 'Requisitions', 'route' => 'hms.requisitions.index', 'active' => 'hms.requisitions.*'],
                        ['label' => 'Stocktakes', 'route' => 'hms.stocktakes.index', 'active' => 'hms.stocktakes.*'],
                    ],
                ],
                [
                    'label' => 'Procurement',
                    'icon' => 'fa-cart-shopping',
                    'children' => [
                        ['label' => 'Suppliers', 'route' => 'hms.inventory.suppliers.index', 'active' => 'hms.inventory.suppliers.*'],
                        ['label' => 'Purchase Orders', 'route' => 'hms.inventory.purchase-orders.index', 'active' => 'hms.inventory.purchase-orders.*'],
                        ['label' => 'RFQs', 'route' => 'hms.procurement.rfqs.index', 'active' => 'hms.procurement.rfqs.*'],
                        ['label' => 'Supplier Invoices', 'route' => 'hms.procurement.supplier-invoices.index', 'active' => 'hms.procurement.supplier-invoices.*'],
                    ],
                ],
                [
                    'label' => 'Stores',
                    'icon' => 'fa-warehouse',
                    'children' => [
                        ['label' => 'All Stores', 'route' => 'hms.stores.index', 'active' => 'hms.stores.index*'],
                        ['label' => 'Stock Transfer', 'route' => 'hms.stores.transfer', 'active' => 'hms.stores.transfer*'],
                        ['label' => 'Store Issues', 'route' => 'hms.stores.issues.index', 'active' => 'hms.stores.issues.*'],
                    ],
                ],
                [
                    'label' => 'Packages & Assets',
                    'icon' => 'fa-box-open',
                    'permission' => ['manage packages', 'manage assets'],
                    'children' => [
                        ['label' => 'Service Packages', 'route' => 'hms.packages.index', 'permission' => ['manage packages'], 'active' => 'hms.packages.*'],
                        ['label' => 'Equipment / Assets', 'route' => 'equipment.index', 'active' => 'equipment.index*'],
                        ['label' => 'Asset Registry', 'route' => 'assets.index', 'active' => 'assets.index*'],
                        ['label' => 'Maintenance Requests', 'route' => 'maintenance.requests.index', 'active' => 'maintenance.requests.index*'],
                        ['label' => 'Maintenance Calibrations', 'route' => 'maintenance.calibrations.index', 'active' => 'maintenance.calibrations.*'],
                        ['label' => 'Linen Records', 'route' => 'hms.linen.records.index', 'active' => 'hms.linen.records.*'],
                        ['label' => 'Kitchen Meals', 'route' => 'hms.kitchen.meals.index', 'active' => 'hms.kitchen.meals.*'],
                        ['label' => 'Kitchen Inventory', 'route' => 'hms.kitchen.inventory.index', 'active' => 'hms.kitchen.inventory.*'],
                    ],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // 5. FINANCE
        // ---------------------------------------------------------------------
        [
            'key' => 'finance',
            'label' => 'Finance',
            'icon' => 'fa-coins',
            'color' => 'cyan',
            'permission' => [
                'create invoices', 'edit invoices', 'add payments', 'view payment reports',
                'view invoices', 'view billing', 'view payments', 'manage advance payments',
                'view financial reports', 'generate financial reports', 'manage bank accounts',
            ],
            'items' => [
                [
                    'label' => 'Billing',
                    'icon' => 'fa-file-invoice-dollar',
                    'children' => [
                        ['label' => 'Billing Dashboard', 'route' => 'hms.billing.index', 'active' => 'hms.billing.index*'],
                        ['label' => 'Generate Bill', 'route' => 'hms.billing.invoices.create', 'active' => 'hms.billing.invoices.create*'],
                        ['label' => 'Invoices', 'route' => 'hms.billing.invoices.index', 'active' => 'hms.billing.invoices.index*'],
                        ['label' => 'Payment Receipts', 'route' => 'hms.billing.receipts', 'active' => 'hms.billing.receipts*'],
                        ['label' => 'Services Catalog', 'route' => 'hms.pricing.services.index', 'active' => 'hms.pricing.services.*'],
                        ['label' => 'Price Lists', 'route' => 'hms.pricing.price-lists.index', 'active' => 'hms.pricing.price-lists.*'],
                    ],
                ],
                [
                    'label' => 'Payments',
                    'icon' => 'fa-credit-card',
                    'children' => [
                        ['label' => 'Payment List', 'route' => 'hms.billing.payments.index', 'active' => 'hms.billing.payments.*'],
                        ['label' => 'Payment Reports', 'route' => 'hms.billing.payment-reports', 'active' => 'hms.billing.payment-reports*'],
                        ['label' => 'Advance Payments', 'route' => 'hms.advance-payments.deposits', 'active' => 'hms.advance-payments.deposits*'],
                        ['label' => 'Refunds', 'route' => 'hms.advance-payments.refunds', 'active' => 'hms.advance-payments.refunds*'],
                        ['label' => 'M-Pesa & Gateways', 'route' => 'hms.integrations.payment-gateways', 'active' => 'hms.integrations.payment-gateways*'],
                    ],
                ],
                [
                    'label' => 'Insurance / SHA',
                    'icon' => 'fa-shield-heart',
                    'children' => [
                        ['label' => 'Insurance Overview', 'route' => 'hms.insurance.index', 'active' => 'hms.insurance.index*'],
                        ['label' => 'Companies', 'route' => 'hms.insurance.companies', 'active' => 'hms.insurance.companies*'],
                        ['label' => 'Providers', 'route' => 'hms.insurance.providers.index', 'active' => 'hms.insurance.providers.*'],
                        ['label' => 'Policies', 'route' => 'hms.insurance.policies', 'active' => 'hms.insurance.policies*'],
                        ['label' => 'Claims', 'route' => 'hms.insurance.claims.index', 'active' => 'hms.insurance.claims.*'],
                        ['label' => 'Tariffs', 'route' => 'hms.insurance.tariffs.index', 'active' => 'hms.insurance.tariffs.*'],
                        ['label' => 'SHA / SHIF', 'route' => 'hms.sha.index', 'active' => 'hms.sha.index*'],
                        ['label' => 'SHA Members', 'route' => 'hms.sha.members', 'active' => 'hms.sha.members*'],
                        ['label' => 'SHA Authorizations', 'route' => 'hms.sha.authorizations', 'active' => 'hms.sha.authorizations*'],
                        ['label' => 'SHA Providers', 'route' => 'hms.sha.providers', 'active' => 'hms.sha.providers*'],
                        ['label' => 'SHA Service Codes', 'route' => 'hms.sha.service-codes', 'active' => 'hms.sha.service-codes*'],
                    ],
                ],
                [
                    'label' => 'Expenses',
                    'icon' => 'fa-money-bill-trend-up',
                    'children' => [
                        ['label' => 'All Expenses', 'route' => 'hms.finance.expenses.index', 'active' => 'hms.finance.expenses.index*'],
                        ['label' => 'Expense Categories', 'route' => 'hms.finance.expenses.categories', 'active' => 'hms.finance.expenses.categories*'],
                        ['label' => 'Expense Reports', 'route' => 'hms.finance.expenses.reports', 'active' => 'hms.finance.expenses.reports*'],
                        ['label' => 'Expense Entries', 'route' => 'hms.finance.expenses.entries', 'active' => 'hms.finance.expenses.entries*'],
                    ],
                ],
                [
                    'label' => 'Advance Payments',
                    'icon' => 'fa-wallet',
                    'children' => [
                        ['label' => 'All Advance Payments', 'route' => 'hms.advance-payments.index', 'active' => 'hms.advance-payments.index*'],
                        ['label' => 'Patient Deposits', 'route' => 'hms.advance-payments.deposits', 'active' => 'hms.advance-payments.deposits*'],
                        ['label' => 'Refunds', 'route' => 'hms.advance-payments.refunds', 'active' => 'hms.advance-payments.refunds*'],
                    ],
                ],
                [
                    'label' => 'Income',
                    'icon' => 'fa-arrow-trend-up',
                    'children' => [
                        ['label' => 'All Income', 'route' => 'hms.finance.income.index', 'active' => 'hms.finance.income.index*'],
                        ['label' => 'Income Reports', 'route' => 'hms.finance.income.reports', 'active' => 'hms.finance.income.reports*'],
                    ],
                ],
                [
                    'label' => 'Accounting',
                    'icon' => 'fa-book',
                    'children' => [
                        ['label' => 'Finance Dashboard', 'route' => 'hms.finance.index', 'active' => 'hms.finance.index*'],
                        ['label' => 'Account Heads', 'route' => 'hms.finance.accounts.index', 'active' => 'hms.finance.accounts.*'],
                        ['label' => 'Chart of Accounts', 'route' => 'hms.finance.chart-of-accounts', 'active' => 'hms.finance.chart-of-accounts*'],
                        ['label' => 'Ledger', 'route' => 'hms.finance.ledger', 'active' => 'hms.finance.ledger*'],
                        ['label' => 'Trial Balance', 'route' => 'hms.finance.trial-balance', 'active' => 'hms.finance.trial-balance*'],
                        ['label' => 'Journal', 'route' => 'hms.journal.index', 'active' => 'hms.journal.*'],
                        ['label' => 'Bank Reconciliation', 'route' => 'hms.finance.bank-reconciliations.index', 'active' => 'hms.finance.bank-reconciliations.*'],
                    ],
                ],
                [
                    'label' => 'Financial Reports',
                    'icon' => 'fa-chart-pie',
                    'children' => [
                        ['label' => 'All Finance Reports', 'route' => 'hms.finance.reports', 'active' => 'hms.finance.reports*'],
                        ['label' => 'Profit & Loss', 'route' => 'hms.finance.profit-loss', 'active' => 'hms.finance.profit-loss*'],
                        ['label' => 'Balance Sheet', 'route' => 'hms.finance.balance-sheet', 'active' => 'hms.finance.balance-sheet*'],
                        ['label' => 'Cash Flow', 'route' => 'hms.finance.cash-flow', 'active' => 'hms.finance.cash-flow*'],
                    ],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // 6. PEOPLE & CONFIGURATION
        // ---------------------------------------------------------------------
        [
            'key' => 'people',
            'label' => 'People & Configuration',
            'icon' => 'fa-users-gear',
            'color' => 'indigo',
            'permission' => [
                'manage staff profiles', 'view attendance', 'manage payrolls',
                'manage system settings', 'manage hospital info',
            ],
            'items' => [
                [
                    'label' => 'HR',
                    'icon' => 'fa-user-tie',
                    'permission' => ['manage staff profiles', 'view attendance', 'manage payrolls'],
                    'children' => [
                        ['label' => 'HR Dashboard', 'route' => 'hms.hr.index', 'active' => 'hms.hr.index*'],
                        ['label' => 'Employees', 'route' => 'hms.hr.employees.index', 'active' => 'hms.hr.employees.index*'],
                        ['label' => 'Add Employee', 'route' => 'hms.hr.employees.create', 'active' => 'hms.hr.employees.create*'],
                        ['label' => 'Designations', 'route' => 'hms.hr.designations.index', 'active' => 'hms.hr.designations.*'],
                        ['label' => 'HR Departments', 'route' => 'hms.hr.departments.index', 'active' => 'hms.hr.departments.*'],
                        ['label' => 'Appraisals', 'route' => 'hms.hr.appraisals.index', 'active' => 'hms.hr.appraisals.*'],
                        ['label' => 'Contracts', 'route' => 'hms.hr.contracts.index', 'active' => 'hms.hr.contracts.*'],
                        ['label' => 'Disciplinary', 'route' => 'hms.hr.disciplinary.index', 'active' => 'hms.hr.disciplinary.*'],
                        ['label' => 'Staff Licences', 'route' => 'hms.hr.licences.index', 'active' => 'hms.hr.licences.*'],
                        ['label' => 'Expiring Licences', 'route' => 'hms.hr.licences.expiring', 'active' => 'hms.hr.licences.expiring*'],
                        ['label' => 'Staff Documents', 'route' => 'hms.hr.staff-documents.index', 'active' => 'hms.hr.staff-documents.*'],
                        ['label' => 'HR Documents', 'route' => 'hms.hr.documents.index', 'active' => 'hms.hr.documents.*'],
                        ['label' => 'Document Types', 'route' => 'hms.hr.document-types', 'active' => 'hms.hr.document-types*'],
                        ['label' => 'Training Trainees', 'route' => 'hms.training.trainees.index', 'active' => 'hms.training.trainees.*'],
                        ['label' => 'Recruitment', 'route' => 'hms.hr.job-postings.index', 'active' => 'hms.hr.job-postings.*'],
                        ['label' => 'Job Applications', 'route' => 'hms.hr.job-applications.index', 'active' => 'hms.hr.job-applications.*'],
                        ['label' => 'Training', 'route' => 'hms.hr.training-programs.index', 'active' => 'hms.hr.training-programs.*'],
                        ['label' => 'Internships', 'route' => 'hms.hr.internships.index', 'active' => 'hms.hr.internships.*'],
                        ['label' => 'Student Rotations', 'route' => 'hms.hr.student-rotations.index', 'active' => 'hms.hr.student-rotations.*'],
                        ['label' => 'Announcements', 'route' => 'hms.hr.announcements.index', 'active' => 'hms.hr.announcements.*'],
                        ['label' => 'Shifts', 'route' => 'hms.hr.shifts.index', 'active' => 'hms.hr.shifts.*'],
                        ['label' => 'Roster / Schedules', 'route' => 'hms.hr.schedules.index', 'active' => 'hms.hr.schedules.*'],
                        ['label' => 'Public Holidays', 'route' => 'hms.hr.public-holidays.index', 'active' => 'hms.hr.public-holidays.*'],
                        ['label' => 'HR Settings', 'route' => 'hms.hr.settings.index', 'active' => 'hms.hr.settings.index*'],
                        ['label' => 'HR Reports', 'route' => 'hms.hr.reports.index', 'active' => 'hms.hr.reports.index*'],
                    ],
                ],
                [
                    'label' => 'Payroll',
                    'icon' => 'fa-money-check-dollar',
                    'permission' => ['manage payrolls'],
                    'children' => [
                        ['label' => 'Generate Salary', 'route' => 'hms.hr.payrolls.create', 'active' => 'hms.hr.payrolls.create*'],
                        ['label' => 'Salary Reports', 'route' => 'hms.hr.payrolls.index', 'active' => 'hms.hr.payrolls.index*'],
                        ['label' => 'Payroll Export', 'route' => 'hms.hr.payroll-export.index', 'active' => 'hms.hr.payroll-export.*'],
                    ],
                ],
                [
                    'label' => 'Leave',
                    'icon' => 'fa-plane-departure',
                    'children' => [
                        ['label' => 'Leave Requests', 'route' => 'hms.hr.leave-requests.index', 'active' => 'hms.hr.leave-requests.*'],
                        ['label' => 'Leave Balances', 'route' => 'hms.hr.leave-balances.index', 'active' => 'hms.hr.leave-balances.*'],
                        ['label' => 'Leave Types', 'route' => 'hms.hr.leave-types.index', 'active' => 'hms.hr.leave-types.*'],
                    ],
                ],
                [
                    'label' => 'Attendance',
                    'icon' => 'fa-clipboard-check',
                    'permission' => ['view attendance'],
                    'children' => [
                        ['label' => 'Daily Logs', 'route' => 'hms.hr.attendance.index', 'active' => 'hms.hr.attendance.*'],
                    ],
                ],
                [
                    'label' => 'Departments',
                    'icon' => 'fa-building',
                    'children' => [
                        ['label' => 'Hospital Departments', 'route' => 'hms.hospital-departments.index', 'active' => 'hms.hospital-departments.*'],
                    ],
                ],
                [
                    'label' => 'Credentialing',
                    'icon' => 'fa-id-card',
                    'children' => [
                        ['label' => 'Credentialing Overview', 'route' => 'hms.credentialing.index', 'active' => 'hms.credentialing.index*'],
                        ['label' => 'Qualifications', 'route' => 'hms.credentialing.qualifications', 'active' => 'hms.credentialing.qualifications*'],
                        ['label' => 'Licences', 'route' => 'hms.credentialing.licences.index', 'active' => 'hms.credentialing.licences.index*'],
                        ['label' => 'Expiring Licences', 'route' => 'hms.credentialing.licences.expiring', 'active' => 'hms.credentialing.licences.expiring*'],
                        ['label' => 'Privileges', 'route' => 'hms.credentialing.privileges.index', 'active' => 'hms.credentialing.privileges.*'],
                        ['label' => 'On-Call Roster', 'route' => 'hms.credentialing.oncall.index', 'active' => 'hms.credentialing.oncall.*'],
                        ['label' => 'CME', 'route' => 'hms.credentialing.cme.index', 'active' => 'hms.credentialing.cme.*'],
                    ],
                ],
                [
                    'label' => 'Facilities & Setup',
                    'icon' => 'fa-hospital',
                    'permission' => ['manage system settings', 'manage hospital info'],
                    'children' => [
                        ['label' => 'Hospital Info', 'route' => 'hms.settings.index', 'active' => 'hms.settings.index*'],
                        ['label' => 'General Settings', 'route' => 'hms.settings.general', 'active' => 'hms.settings.general*'],
                        ['label' => 'Branch Setup', 'route' => 'hms.settings.branches', 'active' => 'hms.settings.branches*'],
                        ['label' => 'Emergency Contacts', 'route' => 'hms.settings.emergency-contacts', 'active' => 'hms.settings.emergency-contacts*'],
                        ['label' => 'Maps', 'route' => 'hms.system.maps', 'active' => 'hms.system.maps*'],
                        ['label' => 'Contact Info', 'route' => 'hms.system.contact-info', 'active' => 'hms.system.contact-info*'],
                        ['label' => 'Timezone', 'route' => 'hms.system.timezone', 'active' => 'hms.system.timezone*'],
                        ['label' => 'Theme / Branding', 'route' => 'hms.settings.theme', 'active' => 'hms.settings.theme*'],
                        ['label' => 'Theme Preview', 'route' => 'hms.system.theme', 'active' => 'hms.system.theme*'],
                        ['label' => 'ID Cards (Patients)', 'route' => 'hms.id-cards.bulk-patients', 'active' => 'hms.id-cards.bulk-patients*'],
                        ['label' => 'ID Cards (Staff)', 'route' => 'hms.id-cards.bulk-employees', 'active' => 'hms.id-cards.bulk-employees*'],
                    ],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // 7. REPORTS & ANALYTICS
        // ---------------------------------------------------------------------
        [
            'key' => 'reports',
            'label' => 'Reports & Analytics',
            'icon' => 'fa-chart-column',
            'color' => 'violet',
            'permission' => [
                'generate patient reports', 'generate billing reports', 'view dashboard analytics',
                'export reports', 'view reports', 'generate financial reports',
                'generate birth reports', 'generate death reports',
            ],
            'items' => [
                [
                    'label' => 'Reports Hub',
                    'icon' => 'fa-folder-open',
                    'children' => [
                        ['label' => 'All Reports', 'route' => 'hms.reports.index', 'active' => 'hms.reports.index*'],
                        ['label' => 'Summary Reports', 'route' => 'hms.reports.summary', 'active' => 'hms.reports.summary*'],
                    ],
                ],
                [
                    'label' => 'Clinical Reports',
                    'icon' => 'fa-stethoscope',
                    'children' => [
                        ['label' => 'Diagnosis Report', 'route' => 'hms.reports.diagnosis', 'active' => 'hms.reports.diagnosis*'],
                        ['label' => 'Doctor Performance', 'route' => 'hms.reports.doctor-performance', 'active' => 'hms.reports.doctor-performance*'],
                        ['label' => 'Bed Occupancy', 'route' => 'hms.reports.bed-occupancy', 'active' => 'hms.reports.bed-occupancy*'],
                        ['label' => 'Appointments Report', 'route' => 'hms.reports.appointments', 'active' => 'hms.reports.appointments*'],
                        ['label' => 'Patients Report', 'route' => 'hms.reports.patients', 'active' => 'hms.reports.patients*'],
                    ],
                ],
                [
                    'label' => 'Diagnostics Reports',
                    'icon' => 'fa-vials',
                    'children' => [
                        ['label' => 'Lab Report', 'route' => 'hms.reports.lab', 'active' => 'hms.reports.lab*'],
                        ['label' => 'Pharmacy Report', 'route' => 'hms.reports.pharmacy', 'active' => 'hms.reports.pharmacy*'],
                        ['label' => 'Blood Bank Report', 'route' => 'hms.reports.blood-bank', 'active' => 'hms.reports.blood-bank*'],
                    ],
                ],
                [
                    'label' => 'Financial Reports',
                    'icon' => 'fa-sack-dollar',
                    'children' => [
                        ['label' => 'Revenue Report', 'route' => 'hms.reports.revenue', 'active' => 'hms.reports.revenue*'],
                        ['label' => 'Billing Report', 'route' => 'hms.reports.billing', 'active' => 'hms.reports.billing*'],
                        ['label' => 'Expense Report', 'route' => 'hms.reports.expense', 'active' => 'hms.reports.expense*'],
                        ['label' => 'Financial Summary', 'route' => 'hms.reports.financial', 'active' => 'hms.reports.financial*'],
                    ],
                ],
                [
                    'label' => 'HR Reports',
                    'icon' => 'fa-users',
                    'children' => [
                        ['label' => 'Employee List', 'route' => 'hms.hr.reports.employee-list', 'active' => 'hms.hr.reports.employee-list*'],
                        ['label' => 'Leave Report', 'route' => 'hms.hr.reports.leave', 'active' => 'hms.hr.reports.leave*'],
                        ['label' => 'Attendance Report', 'route' => 'hms.hr.reports.attendance', 'active' => 'hms.hr.reports.attendance*'],
                        ['label' => 'Payroll Summary', 'route' => 'hms.hr.reports.payroll-summary', 'active' => 'hms.hr.reports.payroll-summary*'],
                        ['label' => 'Headcount Trends', 'route' => 'hms.hr.reports.headcount-trends', 'active' => 'hms.hr.reports.headcount-trends*'],
                        ['label' => 'Attrition Report', 'route' => 'hms.hr.reports.attrition', 'active' => 'hms.hr.reports.attrition*'],
                        ['label' => 'Salary Expense', 'route' => 'hms.hr.reports.salary-expense', 'active' => 'hms.hr.reports.salary-expense*'],
                        ['label' => 'Training Participation', 'route' => 'hms.hr.reports.training-participation', 'active' => 'hms.hr.reports.training-participation*'],
                    ],
                ],
                [
                    'label' => 'Regulatory / MOH',
                    'icon' => 'fa-landmark',
                    'children' => [
                        ['label' => 'MOH Dashboard', 'route' => 'hms.moh-reports.index', 'active' => 'hms.moh-reports.index*'],
                        ['label' => 'MOH OPD Summary', 'route' => 'hms.moh-reports.opd-summary', 'active' => 'hms.moh-reports.opd-summary*'],
                        ['label' => 'MOH IPD Summary', 'route' => 'hms.moh-reports.ipd-summary', 'active' => 'hms.moh-reports.ipd-summary*'],
                        ['label' => 'MOH Disease Surveillance', 'route' => 'hms.moh-reports.disease-surveillance', 'active' => 'hms.moh-reports.disease-surveillance*'],
                        ['label' => 'MOH Maternal Health', 'route' => 'hms.moh-reports.maternal-health', 'active' => 'hms.moh-reports.maternal-health*'],
                        ['label' => 'MOH Pharmacy Consumption', 'route' => 'hms.moh-reports.pharmacy-consumption', 'active' => 'hms.moh-reports.pharmacy-consumption*'],
                        ['label' => 'MOH Revenue Collection', 'route' => 'hms.moh-reports.revenue-collection', 'active' => 'hms.moh-reports.revenue-collection*'],
                        ['label' => 'Birth Reports', 'route' => 'hms.reports.birth', 'active' => 'hms.reports.birth*'],
                        ['label' => 'Death Reports', 'route' => 'hms.reports.death', 'active' => 'hms.reports.death*'],
                    ],
                ],
                [
                    'label' => 'More Reports',
                    'icon' => 'fa-folder-open',
                    'children' => [
                        ['label' => 'Summary Reports', 'route' => 'hms.reports.summary', 'active' => 'hms.reports.summary*'],
                        ['label' => 'Custom Report Builder', 'route' => 'hms.reports.custom-builder.index', 'active' => 'hms.reports.custom-builder.*', 'badge' => 'Pro'],
                        ['label' => 'Saved Reports', 'route' => 'hms.reports.saved.index', 'active' => 'hms.reports.saved.*'],
                        ['label' => 'Export All Data', 'route' => 'hms.reports.export-patients', 'active' => 'hms.reports.export-patients*'],
                    ],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // 8. DIGITAL & INTEGRATIONS
        // ---------------------------------------------------------------------
        [
            'key' => 'digital',
            'label' => 'Digital & Integrations',
            'icon' => 'fa-microchip',
            'color' => 'teal',
            'permission' => [
                'use ai assistant', 'manage ai suggestions', 'use telemedicine',
                'manage rfid tags', 'monitor iot sensors', 'view analytics',
            ],
            'items' => [
                [
                    'label' => 'Telemedicine',
                    'icon' => 'fa-video',
                    'permission' => ['use telemedicine'],
                    'children' => [
                        ['label' => 'Sessions', 'route' => 'telemedicine.index', 'active' => 'telemedicine.index*'],
                        ['label' => 'Upcoming', 'route' => 'telemedicine.upcoming', 'active' => 'telemedicine.upcoming*'],
                    ],
                ],
                [
                    'label' => 'AI Tools',
                    'icon' => 'fa-robot',
                    'permission' => ['use ai assistant', 'manage ai suggestions'],
                    'children' => [
                        ['label' => 'Elliana D (Virtual Nurse)', 'route' => 'ai.elliana-d', 'active' => 'ai.elliana-d.index*'],
                        ['label' => 'Elliana History', 'route' => 'ai.elliana-d.history', 'active' => 'ai.elliana-d.history*'],
                        ['label' => 'Appointment Suggestions', 'route' => 'ai.appointment-suggestions', 'active' => 'ai.appointment-suggestions*'],
                        ['label' => 'Diagnosis Suggestions', 'route' => 'ai.diagnosis-suggestions', 'active' => 'ai.diagnosis-suggestions*'],
                        ['label' => 'Predictive Analytics', 'route' => 'hms.ai.predictive-analytics', 'active' => 'hms.ai.predictive-analytics*'],
                        ['label' => 'Daily Summary', 'route' => 'hms.daily-summary.index', 'active' => 'hms.daily-summary.*'],
                    ],
                ],
                [
                    'label' => 'RFID',
                    'icon' => 'fa-id-badge',
                    'permission' => ['manage rfid tags'],
                    'children' => [
                        ['label' => 'RFID Management', 'route' => 'rfid.index', 'active' => 'rfid.index*'],
                        ['label' => 'Active RFID Tags', 'route' => 'rfid.active', 'active' => 'rfid.active*'],
                    ],
                ],
                [
                    'label' => 'IoT / Bed Monitoring',
                    'icon' => 'fa-satellite-dish',
                    'permission' => ['monitor iot sensors'],
                    'children' => [
                        ['label' => 'Bed Monitoring', 'route' => 'iot.bed-monitoring', 'active' => 'iot.bed-monitoring*'],
                        ['label' => 'Occupancy Map', 'route' => 'iot.bed-occupancy-map', 'active' => 'iot.bed-occupancy-map*'],
                        ['label' => 'IoT Alerts', 'route' => 'iot.alerts', 'active' => 'iot.alerts*'],
                    ],
                ],
                [
                    'label' => 'Integrations & API',
                    'icon' => 'fa-plug',
                    'children' => [
                        ['label' => 'All Integrations', 'route' => 'hms.integrations.index', 'active' => 'hms.integrations.index*'],
                        ['label' => 'Payment Gateways', 'route' => 'hms.integrations.payment-gateways', 'active' => 'hms.integrations.payment-gateways*'],
                        ['label' => 'WhatsApp API', 'route' => 'hms.integrations.whatsapp', 'active' => 'hms.integrations.whatsapp*'],
                        ['label' => 'Google Calendar', 'route' => 'hms.integrations.google-calendar', 'active' => 'hms.integrations.google-calendar*'],
                        ['label' => 'Automated Alerts', 'route' => 'hms.integrations.alerts', 'active' => 'hms.integrations.alerts*'],
                        ['label' => 'Lab Equipment', 'route' => 'integration.lab-equipment', 'active' => 'integration.lab-equipment*'],
                        ['label' => 'Insurance API', 'route' => 'integration.insurance-api', 'active' => 'integration.insurance-api*'],
                        ['label' => 'DHA Superhighway', 'route' => 'integration.dha.index', 'active' => 'integration.dha.*'],
                        ['label' => 'EHR Interoperability', 'route' => 'hms.integration.ehr.index', 'active' => 'hms.integration.ehr.*', 'badge' => 'Pro'],
                        ['label' => 'API Keys', 'route' => 'hms.system.api-keys', 'active' => 'hms.system.api-keys*'],
                        ['label' => 'Data Sync Dashboard', 'route' => 'hms.integrations.data-sync', 'active' => 'hms.integrations.data-sync*'],
                    ],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // 9. CMS & MARKETING
        // ---------------------------------------------------------------------
        [
            'key' => 'cms-marketing',
            'label' => 'CMS & Marketing',
            'icon' => 'fa-bullhorn',
            'color' => 'pink',
            'permission' => [
                'manage homepage', 'manage services', 'manage doctors listing', 'manage marketing',
                'create marketing posts', 'manage campaigns', 'manage social accounts',
            ],
            'items' => [
                [
                    'label' => 'Website CMS',
                    'icon' => 'fa-globe',
                    'children' => [
                        ['label' => 'Home Page', 'route' => 'cms.home', 'active' => 'cms.home*'],
                        ['label' => 'Services Page', 'route' => 'cms.services', 'active' => 'cms.services*'],
                        ['label' => 'Doctors Page', 'route' => 'cms.doctors-page', 'active' => 'cms.doctors-page*'],
                        ['label' => 'About Page', 'route' => 'cms.about', 'active' => 'cms.about*'],
                        ['label' => 'Contact Page', 'route' => 'cms.contact-page', 'active' => 'cms.contact-page*'],
                        ['label' => 'Features Page', 'route' => 'cms.features', 'active' => 'cms.features*'],
                        ['label' => 'Header & Footer', 'route' => 'cms.header-footer', 'active' => 'cms.header-footer*'],
                        ['label' => 'SEO Settings', 'route' => 'cms.seo', 'active' => 'cms.seo*'],
                        ['label' => 'Contact Inquiries', 'route' => 'cms.contact-inquiries', 'active' => 'cms.contact-inquiries*'],
                    ],
                ],
                [
                    'label' => 'Content',
                    'icon' => 'fa-newspaper',
                    'children' => [
                        ['label' => 'News / Blog', 'route' => 'cms.blog.index', 'active' => 'cms.blog.*'],
                        ['label' => 'Gallery', 'route' => 'cms.gallery.index', 'active' => 'cms.gallery.*'],
                        ['label' => 'Testimonials', 'route' => 'cms.testimonials.index', 'active' => 'cms.testimonials.*'],
                        ['label' => 'Careers', 'route' => 'cms.careers.index', 'active' => 'cms.careers.index*'],
                        ['label' => 'Career Applications', 'route' => 'cms.careers.applications', 'active' => 'cms.careers.applications*'],
                    ],
                ],
                [
                    'label' => 'Marketing',
                    'icon' => 'fa-bullhorn',
                    'permission' => ['manage marketing', 'create marketing posts', 'manage campaigns', 'manage social accounts'],
                    'children' => [
                        ['label' => 'Marketing Dashboard', 'route' => 'marketing.dashboard', 'active' => 'marketing.dashboard*'],
                        ['label' => 'AI Content Writer', 'route' => 'marketing.posts.index', 'active' => 'marketing.posts.*'],
                        ['label' => 'Campaigns', 'route' => 'marketing.campaigns.index', 'active' => 'marketing.campaigns.*'],
                        ['label' => 'Scheduler', 'route' => 'marketing.scheduler.index', 'active' => 'marketing.scheduler.*'],
                        ['label' => 'Social Accounts', 'route' => 'marketing.social-accounts.index', 'active' => 'marketing.social-accounts.*'],
                        ['label' => 'Comment Replies', 'route' => 'marketing.comments.index', 'active' => 'marketing.comments.*'],
                        ['label' => 'Graphics & Assets', 'route' => 'marketing.graphics.index', 'active' => 'marketing.graphics.*'],
                        ['label' => 'SEO Manager', 'route' => 'marketing.seo.index', 'active' => 'marketing.seo.*'],
                    ],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // 10. ADMINISTRATION (BOTTOM)
        // ---------------------------------------------------------------------
        [
            'key' => 'administration',
            'label' => 'Administration',
            'icon' => 'fa-cog',
            'color' => 'slate',
            'permission' => [
                'manage system settings', 'manage roles', 'manage permissions', 'view audit logs',
                'manage backups', 'manage user accounts', 'manage hospital info',
                'manage security incidents', 'manage lost found items', 'manage access events',
            ],
            'items' => [
                [
                    'label' => 'Users & Roles',
                    'icon' => 'fa-user-shield',
                    'permission' => ['manage roles', 'manage user accounts', 'manage staff profiles'],
                    'children' => [
                        ['label' => 'Users', 'route' => 'hms.system.users.index', 'active' => 'hms.system.users.*'],
                        ['label' => 'Roles & Permissions', 'route' => 'admin.roles.index', 'permission' => ['manage roles'], 'active' => 'admin.roles.index*'],
                        ['label' => 'All Permissions', 'route' => 'admin.roles.all-permissions', 'permission' => ['manage roles'], 'active' => 'admin.roles.all-permissions*'],
                    ],
                ],
                [
                    'label' => 'System Settings',
                    'icon' => 'fa-sliders',
                    'permission' => ['manage system settings', 'manage roles'],
                    'children' => [
                        ['label' => 'Modules', 'route' => 'admin.modules.index', 'permission' => ['manage roles'], 'active' => 'admin.modules.*'],
                        ['label' => 'Localization', 'route' => 'hms.system.localization', 'permission' => ['manage roles'], 'active' => 'hms.system.localization*'],
                        ['label' => 'Multi-Currency', 'route' => 'admin.modules.multi-currency.index', 'permission' => ['manage roles'], 'active' => 'admin.modules.multi-currency.*'],
                    ],
                ],
                [
                    'label' => 'Communication Ops',
                    'icon' => 'fa-envelope-open-text',
                    'children' => [
                        ['label' => 'Notice Board', 'route' => 'admin.notices.index', 'active' => 'admin.notices.*'],
                        ['label' => 'Staff Notices', 'route' => 'hms.notices.staff', 'active' => 'hms.notices.staff*'],
                        ['label' => 'Messages Dashboard', 'route' => 'hms.messaging.index', 'active' => 'hms.messaging.index*'],
                        ['label' => 'Comms Messages', 'route' => 'hms.comms.messages.index', 'active' => 'hms.comms.messages.*'],
                        ['label' => 'Bulk Messages', 'route' => 'hms.messaging.bulk', 'active' => 'hms.messaging.bulk*'],
                        ['label' => 'Message Templates', 'route' => 'hms.messaging.templates', 'active' => 'hms.messaging.templates*'],
                        ['label' => 'Reminders', 'route' => 'hms.reminders.index', 'active' => 'hms.reminders.index*'],
                        ['label' => 'Appointment Reminders', 'route' => 'hms.reminders.appointments', 'active' => 'hms.reminders.appointments*'],
                        ['label' => 'Payment Reminders', 'route' => 'hms.reminders.payments', 'active' => 'hms.reminders.payments*'],
                    ],
                ],
                [
                    'label' => 'Audit & Security',
                    'icon' => 'fa-user-lock',
                    'permission' => ['view audit logs', 'manage security incidents'],
                    'children' => [
                        ['label' => 'Audit Logs', 'route' => 'hms.settings.audit-logs', 'active' => 'hms.settings.audit-logs*'],
                        ['label' => 'Break-Glass Access', 'route' => 'break-glass.index', 'active' => 'break-glass.*'],
                        ['label' => 'Security Incidents', 'route' => 'incidents.index', 'active' => 'incidents.*'],
                        ['label' => 'Access Events', 'route' => 'access-events.index', 'active' => 'access-events.*'],
                        ['label' => 'Lost & Found', 'route' => 'lost-found.index', 'active' => 'lost-found.*'],
                        ['label' => 'Biometric', 'route' => 'biometric.index', 'active' => 'biometric.index*'],
                        ['label' => 'Card Scanner', 'route' => 'card-scanner.index', 'active' => 'card-scanner.index*'],
                        ['label' => 'Vehicle Access', 'route' => 'vehicles.index', 'active' => 'vehicles.index*'],
                    ],
                ],
                [
                    'label' => 'Quality & Safety',
                    'icon' => 'fa-shield-halved',
                    'children' => [
                        ['label' => 'Quality Complaints', 'route' => 'hms.quality.complaints.index', 'active' => 'hms.quality.complaints.*'],
                        ['label' => 'Quality Incidents', 'route' => 'hms.quality.incidents.index', 'active' => 'hms.quality.incidents.*'],
                        ['label' => 'Mortality Reviews', 'route' => 'hms.quality.mortality-reviews.index', 'active' => 'hms.quality.mortality-reviews.*'],
                        ['label' => 'Quality Indicators', 'route' => 'hms.quality.indicators.index', 'active' => 'hms.quality.indicators.*'],
                        ['label' => 'IPC Audits', 'route' => 'hms.ipc.audits.index', 'active' => 'hms.ipc.audits.*'],
                        ['label' => 'IPC HAI Surveillance', 'route' => 'hms.ipc.hai-surveillance.index', 'active' => 'hms.ipc.hai-surveillance.*'],
                        ['label' => 'OHS Safety Incidents', 'route' => 'hms.ohs.safety-incidents.index', 'active' => 'hms.ohs.safety-incidents.*'],
                        ['label' => 'OHS Fire Equipment', 'route' => 'hms.ohs.fire-equipment.index', 'active' => 'hms.ohs.fire-equipment.*'],
                        ['label' => 'OHS Risk Assessments', 'route' => 'hms.ohs.risk-assessments.index', 'active' => 'hms.ohs.risk-assessments.*'],
                    ],
                ],
                [
                    'label' => 'Research & Training',
                    'icon' => 'fa-graduation-cap',
                    'children' => [
                        ['label' => 'Research Projects', 'route' => 'hms.research.projects.index', 'active' => 'hms.research.projects.*'],
                        ['label' => 'Research Publications', 'route' => 'hms.research.publications.index', 'active' => 'hms.research.publications.*'],
                        ['label' => 'Training Trainees', 'route' => 'hms.training.trainees.index', 'active' => 'hms.training.trainees.*'],
                    ],
                ],
                [
                    'label' => 'Backup & Restore',
                    'icon' => 'fa-database',
                    'permission' => ['manage backups'],
                    'children' => [
                        ['label' => 'Backup & Restore', 'route' => 'hms.settings.backup', 'active' => 'hms.settings.backup*'],
                        ['label' => 'ICT Backups', 'route' => 'hms.ict.backups.index', 'active' => 'hms.ict.backups.*'],
                    ],
                ],
                [
                    'label' => 'Support Tools',
                    'icon' => 'fa-screwdriver-wrench',
                    'children' => [
                        ['label' => 'ICT Assets', 'route' => 'hms.ict.assets.index', 'active' => 'hms.ict.assets.*'],
                        ['label' => 'ICT Tickets', 'route' => 'hms.ict.tickets.index', 'active' => 'hms.ict.tickets.*'],
                        ['label' => 'Software Licences', 'route' => 'hms.ict.software-licences.index', 'active' => 'hms.ict.software-licences.*'],
                        ['label' => 'Batch Operations', 'route' => 'hms.batch.index', 'active' => 'hms.batch.*'],
                    ],
                ],
            ],
        ],

    ],

];
