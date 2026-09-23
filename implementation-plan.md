# Implementation plan — hospital management system

Mark each item as it is confirmed or completed: [ ] not started, [x] done. This plan assumes gaps.md has already been filled in against the actual code before work starts.

## Phase 0 — confirmation

- [x] Run the full checklist in gaps.md against the repository.
- [x] Fill status and notes for every row.
- [x] Complete the summary section in gaps.md.
- [x] Rank missing items by operational risk (patient safety and revenue first, reporting last).

## Phase 1 — core patient flow gaps

- [ ] Fix or build any missing step in the outpatient-with-diagnostics flow.
- [ ] Fix or build any missing step in the outpatient-without-diagnostics flow.
- [ ] Fix or build any missing step in the inpatient flow, including bed allocation.
- [x] Build death-in-ward → mortuary flow if missing.
- [x] Build discharge-against-medical-advice path if missing.
- [x] Confirm cash-pay and insurance billing paths both work end to end.

## Phase 2 — stock and supply chain

- [x] Confirm main store and all required sub-stores exist as separate stock locations.
- [x] Build requisition-with-approval flow between sub-store and main store if missing.
- [x] Build or fix procurement flow: request → approval/rejection → LPO → GRN → stock posting.
- [x] Add reorder level and low-stock alerting if missing.
- [x] Add batch and expiry tracking per item if missing.
- [x] Build expiry notification job: 1 month and 1 week before expiry, delivered by email and shown on dashboard.
- [x] Build stocktake sheet generation (item, unit, system qty, physical qty, variance, remarks).
- [x] Build physical count entry with automatic variance calculation.
- [x] Build stock adjustment workflow with approval, tied to stocktake results.
- [x] Add damaged/expired/write-off recording if missing.

## Phase 3 — HR and staffing

- [x] Build or fix leave application and approval chain.
- [x] Add leave balance tracking by leave type.
- [x] Build duty roster with shift and department assignment.
- [x] Add roster conflict checking.
- [x] Build appraisal cycle, forms, and sign-off.
- [x] Build student rotation scheduling and end-of-rotation evaluation.
- [x] Add internship/attachment tracking if distinct from rotations.

## Phase 4 — administration and reporting

- [x] Confirm role-based access control covers every staff role listed in gaps.md.
- [x] Confirm audit trail captures stock adjustments, discharges, and billing changes.
- [x] Build or fix required MOH and internal reports.
- [x] Confirm the notification engine is reusable (not hardcoded to one alert type) so it can serve expiry alerts, low stock, and pending approvals from one system.

## Phase 5 — verification

- [ ] Re-run the gaps.md checklist after fixes and confirm each previously Missing/Partial item is now Done.
- [ ] Walk through case 1 (outpatient with diagnostics) start to finish in the live system.
- [ ] Walk through case 2 (outpatient without diagnostics) start to finish.
- [ ] Walk through case 3 (inpatient) start to finish, including a ward transfer and a discharge.
- [ ] Run one full requisition-to-GRN cycle in stores.
- [ ] Run one full stocktake on a test sub-store and confirm the variance report is correct.
- [ ] Trigger an expiry alert manually (set a test item to expire in under a month) and confirm both email and dashboard notification fire.
- [ ] Submit one leave request through approval and confirm balance updates.
- [ ] Assign one student to a rotation and complete an evaluation.

## Sign-off

- [ ] All phases above complete.
- [ ] gaps.md summary shows zero Missing items in patient-safety and billing categories.
- [ ] Remaining gaps, if any, listed here with owner and target date:
