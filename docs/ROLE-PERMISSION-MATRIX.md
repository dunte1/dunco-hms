# Dunco HMS — Role & Permission Matrix

## Roles (21)

| # | Role | Description |
|---|------|-------------|
| 1 | Super Admin | Full system access |
| 2 | Hospital Admin | Hospital operations management |
| 3 | Doctor | Patient care and medical operations |
| 4 | Nurse | Patient care and assistance |
| 5 | Receptionist | Front desk operations |
| 6 | Pharmacist | Medicine management |
| 7 | Lab Technician | Laboratory operations |
| 8 | Radiologist | Radiology operations |
| 9 | Accountant | Financial operations |
| 10 | Case Handler | Insurance and case management |
| 11 | Ambulance Operator | Ambulance services |
| 12 | HR Officer | Human resources |
| 13 | Patient | Limited access to own data |
| 14 | System Auditor | Read-only access |
| 15 | Support Staff | Minimal access |
| 16 | Telemedicine Doctor | Online consultations only |
| 17 | Inventory Manager | Stock management |
| 18 | Procurement Officer | Purchasing |
| 19 | IT Support | System maintenance |
| 20 | Marketing Manager | CMS and communication |
| 21 | System AI Bot | Automated operations |

## Permission Summary

93 permissions across 16 categories: Patient Management, Appointments, Prescriptions, Lab & Radiology, Billing, IPD/OPD, Doctors & Staff, Beds, Accounting, Reports, Settings, CMS, Multi-Hospital, AI, Administration, Marketing.

## Role-Permission Matrix

| Permission | SuperAdmin | HospAdmin | Doctor | Nurse | Receptionist | Pharmacist | LabTech | Radiologist | Accountant | Patient |
|---|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| view_patients | Y | Y | Y | Y | Y | Y | Y | Y | Y | Own |
| add_patients | Y | Y | - | - | Y | - | - | - | - | - |
| edit_patients | Y | Y | Y | Y | Y | - | - | - | - | - |
| manage_admissions | Y | Y | Y | Y | - | - | - | - | - | - |
| view_appointments | Y | Y | Y | Y | Y | - | - | - | Y | Own |
| manage_medicines | Y | Y | - | - | - | Y | - | - | - | - |
| view_lab_reports | Y | Y | Y | Y | - | - | Y | Y | - | Own |
| manage_lab_requests | Y | Y | Y | - | - | - | Y | - | - | - |
| view_billing | Y | Y | Y | - | Y | - | - | - | Y | Own |
| manage_invoices | Y | Y | - | - | Y | - | - | - | Y | - |
| manage_payments | Y | Y | - | - | - | - | - | - | Y | - |
| view_reports | Y | Y | Y | - | Y | Y | Y | Y | Y | Own |
| manage_settings | Y | Y | - | - | - | - | - | - | - | - |
| manage_users | Y | Y | - | - | - | - | - | - | - | - |
| manage_roles | Y | - | - | - | - | - | - | - | - | - |
| manage_blog | Y | Y | - | - | - | - | - | - | - | - |

Y = Full access, Own = Own records only, - = No access
