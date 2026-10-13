<?php

namespace App\Services;

use App\Models\LabRequest;
use App\Models\OpdVisit;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\QueueManagement;
use App\Models\Triage;
use Illuminate\Support\Facades\Log;

class PatientFlowService
{
    /**
     * Allowed OPD visit status transitions (hybrid journey state machine).
     */
    public const OPD_TRANSITIONS = [
        'registered' => ['triaged', 'in_consultation', 'lab_pending', 'pharmacy_pending', 'billing_pending', 'completed', 'discharged'],
        'triaged' => ['in_consultation', 'lab_pending', 'pharmacy_pending', 'billing_pending', 'completed', 'discharged'],
        'in_consultation' => ['lab_pending', 'pharmacy_pending', 'billing_pending', 'completed', 'discharged'],
        'lab_pending' => ['in_consultation', 'pharmacy_pending', 'billing_pending', 'completed', 'discharged'],
        'pharmacy_pending' => ['in_consultation', 'lab_pending', 'billing_pending', 'completed', 'discharged'],
        'billing_pending' => ['in_consultation', 'completed', 'discharged'],
        'completed' => [],
        'discharged' => [],
    ];

    public const QUEUE_FOR_OPD = [
        'registered' => 'waiting',
        'triaged' => 'waiting',
        'in_consultation' => 'in_progress',
        'lab_pending' => 'in_progress',
        'pharmacy_pending' => 'in_progress',
        'billing_pending' => 'in_progress',
        'completed' => 'completed',
        'discharged' => 'completed',
    ];

    /**
     * After reception registration: create OPD visit + optional queue token.
     */
    public function afterRegistration(Patient $patient, array $options = []): array
    {
        $result = [
            'opd_visit_id' => null,
            'queue_id' => null,
            'messages' => [],
        ];

        try {
            $opd = OpdVisit::create([
                'patient_id' => $patient->id,
                'visit_date' => now()->toDateString(),
                'visit_time' => now()->format('H:i:s'),
                'status' => 'registered',
                'notes' => $options['notes'] ?? 'Auto-created at reception registration',
            ]);
            $result['opd_visit_id'] = $opd->id;
            $result['messages'][] = 'OPD visit created (' . $opd->id . ').';

            $queueType = $options['queue_type'] ?? 'walk_in';
            $department = $options['department'] ?? 'General';

            $queue = $this->createQueueToken($patient, $department, $queueType, null, $opd->id);
            if ($queue) {
                $result['queue_id'] = $queue->id;
                $result['messages'][] = 'Queue token generated: ' . $queue->token_number;
            }

            $opd->update(['notes' => ($opd->notes ? $opd->notes . ' | ' : '') . 'Ready for triage']);
        } catch (\Throwable $e) {
            Log::error('Patient flow afterRegistration failed', [
                'patient_id' => $patient->id,
                'error' => $e->getMessage(),
            ]);
            $result['messages'][] = 'Auto-handoff incomplete: ' . $e->getMessage();
        }

        return $result;
    }

    public function createQueueToken(
        Patient $patient,
        string $department = 'General',
        string $queueType = 'walk_in',
        $doctorId = null,
        $opdVisitId = null
    ): ?QueueManagement {
        try {
            $departmentCode = strtoupper(substr($department, 0, 3));
            $date = date('Ymd');
            $lastQueue = QueueManagement::where('queue_number', 'like', $departmentCode . '-' . $date . '%')
                ->orderBy('queue_number', 'desc')
                ->first();
            $nextNumber = $lastQueue ? intval(substr($lastQueue->queue_number, -4)) + 1 : 1;

            return QueueManagement::create([
                'patient_id' => $patient->id,
                'patient_name' => trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? '')) ?: ('Patient ' . $patient->id),
                'doctor_id' => $doctorId,
                'department' => $department,
                'queue_type' => $queueType,
                'queue_number' => $departmentCode . '-' . $date . '-' . str_pad($nextNumber, 4, '0'),
                'token_number' => str_pad($nextNumber, 4, '0'),
                'status' => 'waiting',
                'priority' => $queueType === 'emergency' ? 'emergency' : 'normal',
                'check_in_time' => now(),
                'opd_visit_id' => $opdVisitId,
            ]);
        } catch (\Throwable $e) {
            Log::error('createQueueToken failed', ['patient_id' => $patient->id, 'error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * After triage: push patient into queue + mark OPD triaged.
     */
    public function afterTriage(Triage $triage): void
    {
        $patient = $triage->patient;
        if (! $patient) {
            return;
        }

        $department = $triage->opdVisit?->department
            ?? $triage->ipdAdmission?->department
            ?? 'General';

        $queueType = $triage->priority_level === 'emergency' ? 'emergency' : 'walk_in';

        $alreadyQueued = QueueManagement::where('patient_id', $patient->id)
            ->whereIn('status', ['waiting', 'called', 'in_progress'])
            ->exists();

        if (! $alreadyQueued) {
            $this->createQueueToken(
                $patient,
                is_string($department) ? $department : 'General',
                $queueType,
                null,
                $triage->opd_visit_id
            );
        }

        if ($triage->opd_visit_id) {
            $opd = OpdVisit::find($triage->opd_visit_id);
            if ($opd) {
                $this->advanceOpd($opd, 'triaged', [
                    'triage_notes' => $triage->triage_notes ?? $triage->chief_complaint ?? 'Triaged',
                ]);
            }
        } else {
            $opd = OpdVisit::where('patient_id', $patient->id)
                ->whereNotIn('status', ['completed', 'discharged'])
                ->latest('id')
                ->first();

            if ($opd) {
                $this->advanceOpd($opd, 'triaged');
                if (! $triage->opd_visit_id) {
                    $triage->update(['opd_visit_id' => $opd->id]);
                }
            }
        }
    }

    /**
     * After prescription: OPD → pharmacy_pending + pharmacy alert.
     */
    public function afterPrescription(Prescription $prescription): void
    {
        $opd = $this->resolveOpenOpd($prescription->opd_visit_id, $prescription->patient_id);
        $this->advanceOpd($opd, 'pharmacy_pending');

        try {
            \App\Models\InAppAlert::create([
                'type' => 'pharmacy_pending',
                'title' => 'New prescription ready for dispensing',
                'body' => 'Prescription #' . $prescription->id . ' for patient #' . $prescription->patient_id,
                'target_role' => 'Pharmacist',
                'related_id' => $prescription->id,
                'related_type' => 'Prescription',
            ]);
        } catch (\Throwable $e) {
            Log::warning('Could not write pharmacy alert for prescription', [
                'id' => $prescription->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * After lab request created: OPD → lab_pending + lab alert.
     */
    public function afterLabRequest(LabRequest $labRequest): void
    {
        $opd = $this->resolveOpenOpd($labRequest->opd_visit_id, $labRequest->patient_id);

        if ($opd && empty($labRequest->opd_visit_id)) {
            try {
                $labRequest->update(['opd_visit_id' => $opd->id]);
            } catch (\Throwable $e) {
                Log::warning('Could not link lab request to OPD visit', [
                    'lab_request_id' => $labRequest->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->advanceOpd($opd, 'lab_pending');

        try {
            \App\Models\InAppAlert::create([
                'type' => 'lab_pending',
                'title' => 'New lab request pending',
                'body' => 'Lab request ' . $labRequest->request_number . ' for patient #' . $labRequest->patient_id,
                'target_role' => 'Lab Technician',
                'related_id' => $labRequest->id,
                'related_type' => 'LabRequest',
            ]);
        } catch (\Throwable $e) {
            Log::warning('Could not write lab alert', [
                'id' => $labRequest->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * After lab results complete: return OPD to in_consultation (if still lab_pending).
     * Notifications are handled by LabRequestObserver when status flips to completed.
     */
    public function afterLabResultsComplete(LabRequest $labRequest): void
    {
        $opd = $this->resolveOpenOpd($labRequest->opd_visit_id, $labRequest->patient_id);

        if (! $opd) {
            return;
        }

        if ($opd->status === 'lab_pending') {
            $this->advanceOpd($opd, 'in_consultation');
        }
    }

    /**
     * After prescription dispensed: if no remaining active Rx or open labs,
     * advance OPD to billing_pending (awaiting payment / finalize).
     * Does NOT auto-discharge — staff finalize manually.
     */
    public function afterDispense(Prescription $prescription): void
    {
        $opd = $this->resolveOpenOpd($prescription->opd_visit_id, $prescription->patient_id);
        if (! $opd) {
            return;
        }

        if ($this->hasOpenLabForVisit($opd->id, $prescription->patient_id)) {
            return;
        }

        if ($this->hasActivePrescription($prescription, $opd->id, $prescription->patient_id)) {
            return;
        }

        $this->advanceOpd($opd, 'billing_pending');
    }

    /**
     * Manual doctor action: start consultation.
     */
    public function startConsultation(OpdVisit $opd): bool
    {
        return $this->advanceOpd($opd, 'in_consultation');
    }

    /**
     * Manual finalize: OPD completed + linked queue completed.
     */
    public function finalizeVisit(OpdVisit $opd): bool
    {
        $advanced = $this->advanceOpd($opd, 'completed', [
            'finalized_at' => now(),
            'finalized_by' => auth()->id(),
        ]);

        $this->completeQueueForOpd($opd->id);

        return $advanced;
    }

    /**
     * Advance OPD status if the transition is legal; sync linked queue.
     */
    public function advanceOpd(?OpdVisit $opd, string $toStatus, array $extra = []): bool
    {
        if (! $opd) {
            return false;
        }

        if ($opd->status === $toStatus) {
            if ($extra !== []) {
                $opd->update($extra);
            }
            $this->syncQueueForOpd($opd);

            return true;
        }

        $allowed = self::OPD_TRANSITIONS[$opd->status] ?? [];
        if (! in_array($toStatus, $allowed, true)) {
            Log::info('OPD status transition skipped (illegal)', [
                'opd_visit_id' => $opd->id,
                'from' => $opd->status,
                'to' => $toStatus,
            ]);

            return false;
        }

        $opd->update(array_merge(['status' => $toStatus], $extra));
        $this->syncQueueForOpd($opd);

        return true;
    }

    /**
     * Keep the linked queue token roughly in step with OPD journey status.
     */
    public function syncQueueForOpd(OpdVisit $opd): void
    {
        try {
            $queueStatus = self::QUEUE_FOR_OPD[$opd->status] ?? null;
            if (! $queueStatus) {
                return;
            }

            $queue = QueueManagement::where('opd_visit_id', $opd->id)
                ->whereIn('status', ['waiting', 'called', 'in_progress'])
                ->latest('id')
                ->first();

            if (! $queue) {
                return;
            }

            if ($queue->status === $queueStatus) {
                return;
            }

            // Do not yank a just-called token back to waiting
            if ($queue->status === 'called' && $queueStatus === 'waiting') {
                return;
            }

            $payload = ['status' => $queueStatus];
            if ($queueStatus === 'completed' && ! $queue->completed_time) {
                $payload['completed_time'] = now();
            }
            if ($queueStatus === 'in_progress' && ! $queue->called_time) {
                $payload['called_time'] = now();
            }

            $queue->update($payload);
        } catch (\Throwable $e) {
            Log::warning('Queue sync from OPD failed', [
                'opd_visit_id' => $opd->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * When queue service starts, mark linked OPD in_consultation (manual button side-effect).
     */
    public function afterQueueServiceStart(QueueManagement $queue): void
    {
        $opd = $queue->opdVisit_id
            ? OpdVisit::find($queue->opd_visit_id)
            : OpdVisit::where('patient_id', $queue->patient_id)
                ->whereNotIn('status', ['completed', 'discharged'])
                ->latest('id')
                ->first();

        if (! $opd) {
            return;
        }

        if (in_array($opd->status, ['registered', 'triaged'], true)) {
            $this->advanceOpd($opd, 'in_consultation');
        }
    }

    /**
     * When queue is called, start consultation if visit is still pre-consult.
     */
    public function afterQueueCalled(QueueManagement $queue): void
    {
        $this->afterQueueServiceStart($queue);
    }

    /**
     * When queue completes: if no open labs/rx, finalize OPD to completed.
     */
    public function afterQueueCompleted(QueueManagement $queue): void
    {
        $opd = $queue->opd_visit_id
            ? OpdVisit::find($queue->opd_visit_id)
            : OpdVisit::where('patient_id', $queue->patient_id)
                ->whereNotIn('status', ['completed', 'discharged'])
                ->latest('id')
                ->first();

        if (! $opd) {
            return;
        }

        if (! in_array($opd->status, ['in_consultation', 'triaged', 'registered'], true)) {
            return;
        }

        if ($this->hasOpenLabForVisit($opd->id, $opd->patient_id)) {
            return;
        }

        if ($this->hasActivePrescriptionForVisit($opd->id, $opd->patient_id)) {
            return;
        }

        $this->advanceOpd($opd, 'completed', [
            'finalized_at' => $opd->finalized_at ?? now(),
            'finalized_by' => $opd->finalized_by ?? auth()->id(),
        ]);
    }

    /**
     * Complete any open queue token linked to an OPD visit.
     */
    public function completeQueueForOpd(int $opdVisitId): void
    {
        try {
            QueueManagement::where('opd_visit_id', $opdVisitId)
                ->whereIn('status', ['waiting', 'called', 'in_progress'])
                ->update([
                    'status' => 'completed',
                    'completed_time' => now(),
                ]);
        } catch (\Throwable $e) {
            Log::warning('completeQueueForOpd failed', [
                'opd_visit_id' => $opdVisitId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Doctor worklist snapshot.
     */
    public function doctorWorklist($user = null): array
    {
        $today = now()->toDateString();

        $opdVisits = OpdVisit::with(['patient', 'doctor'])
            ->whereDate('visit_date', $today)
            ->whereIn('status', ['registered', 'triaged', 'in_consultation', 'lab_pending', 'pharmacy_pending', 'billing_pending'])
            ->orderByRaw("FIELD(status,'in_consultation','triaged','lab_pending','pharmacy_pending','billing_pending','registered')")
            ->latest('id')
            ->limit(50)
            ->get();

        $queue = QueueManagement::with(['patient', 'doctor'])
            ->whereIn('status', ['waiting', 'called', 'in_progress'])
            ->orderByRaw("FIELD(priority,'emergency','high','normal','low')")
            ->orderBy('check_in_time')
            ->limit(50)
            ->get();

        $labPending = LabRequest::with(['patient', 'doctor'])
            ->where('status', 'pending')
            ->latest()
            ->limit(30)
            ->get();

        $pharmacyPending = Prescription::with(['patient', 'doctor'])
            ->where('status', 'active')
            ->latest()
            ->limit(30)
            ->get();

        return [
            'opd_visits' => $opdVisits,
            'queue' => $queue,
            'lab_pending' => $labPending,
            'pharmacy_pending' => $pharmacyPending,
        ];
    }

    private function resolveOpenOpd(?int $opdVisitId, $patientId): ?OpdVisit
    {
        if ($opdVisitId) {
            $opd = OpdVisit::find($opdVisitId);
            if ($opd && ! in_array($opd->status, ['completed', 'discharged'], true)) {
                return $opd;
            }
        }

        return OpdVisit::where('patient_id', $patientId)
            ->whereNotIn('status', ['completed', 'discharged'])
            ->latest('id')
            ->first();
    }

    private function hasOpenLabForVisit(?int $opdVisitId, $patientId): bool
    {
        $openStatuses = ['pending', 'in_progress', 'verified'];

        if ($opdVisitId) {
            $byVisit = LabRequest::where('opd_visit_id', $opdVisitId)
                ->whereIn('status', $openStatuses)
                ->exists();
            if ($byVisit) {
                return true;
            }
        }

        return LabRequest::where('patient_id', $patientId)
            ->whereNull('opd_visit_id')
            ->whereIn('status', $openStatuses)
            ->exists();
    }

    private function hasActivePrescription(Prescription $justDispensed, ?int $opdVisitId, $patientId): bool
    {
        $query = Prescription::where('id', '!=', $justDispensed->id)
            ->where('status', 'active');

        if ($opdVisitId) {
            $byVisit = (clone $query)->where('opd_visit_id', $opdVisitId)->exists();
            if ($byVisit) {
                return true;
            }
        }

        return (clone $query)
            ->where('patient_id', $patientId)
            ->whereNull('opd_visit_id')
            ->exists();
    }

    private function hasActivePrescriptionForVisit(?int $opdVisitId, $patientId): bool
    {
        $open = Prescription::where('status', 'active');

        if ($opdVisitId) {
            $byVisit = (clone $open)->where('opd_visit_id', $opdVisitId)->exists();
            if ($byVisit) {
                return true;
            }
        }

        return (clone $open)
            ->where('patient_id', $patientId)
            ->whereNull('opd_visit_id')
            ->exists();
    }
}
