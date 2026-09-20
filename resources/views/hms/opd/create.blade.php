@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">New OPD Visit</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('hms.opd.store') }}" method="POST" id="opd-visit-form">
                        @csrf
                        <input type="hidden" name="billing_mode" id="opd-billing-mode" value="">
                        <input type="hidden" name="payment_id" id="opd-payment-id" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Patient</label>
                                    <select name="patient_id" id="opd_patient_id" class="form-control" required>
                                        <option value="">Select Patient</option>
                                        @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" data-phone="{{ $patient->phone }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="opd-coverage-badge" class="form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Doctor</label>
                                    <select name="doctor_id" id="opd_doctor_id" class="form-control">
                                        <option value="">Select Doctor</option>
                                        @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" data-fee="{{ $doctor->consultation_fee }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Visit Date</label>
                                    <input type="datetime-local" name="visit_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Visit Type</label>
                                    <select name="visit_type" id="opd_visit_type" class="form-control" required>
                                        <option value="consultation">Consultation</option>
                                        <option value="follow_up">Follow Up</option>
                                        <option value="emergency">Emergency</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Chief Complaint</label>
                                    <textarea name="chief_complaint" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Consultation Fee</label>
                                    <input type="number" name="consultation_fee" id="opd_consultation_fee" step="0.01" class="form-control" value="{{ $defaultConsultationFee }}">
                                    <div class="form-text">Auto-fills from the selected doctor, or the facility default.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Diagnosis</label>
                                    <textarea name="diagnosis" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prescription</label>
                                    <textarea name="prescription" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Consultation billing --}}
                        <div class="card border-success mt-3">
                            <div class="card-body">
                                <h5 class="card-title">Consultation Billing</h5>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="fw-bold me-2">Consultation Charge:</span>
                                    <span class="fs-4 fw-bold text-success" id="opd-fee-display">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh ') }}{{ number_format($defaultConsultationFee, 2) }}</span>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="opd_billing" id="opd_billing_sha" value="sha">
                                    <label class="form-check-label" for="opd_billing_sha">SHA (covered)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="opd_billing" id="opd_billing_mpesa" value="mpesa">
                                    <label class="form-check-label" for="opd_billing_mpesa">M-Pesa</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="opd_billing" id="opd_billing_cash" value="cash">
                                    <label class="form-check-label" for="opd_billing_cash">Cash at Desk</label>
                                </div>
                                <div id="opd-sha-error" class="alert alert-warning mt-2 mb-0" style="display:none;font-size:13px;">
                                    This patient is not covered for consultation under SHA. Please choose M-Pesa or cash.
                                </div>
                                <div id="opd-mpesa-action" style="display:none;margin-top:12px;">
                                    <button type="button" class="btn btn-success" onclick="startOpdMpesaPayment()">Pay with M-Pesa</button>
                                    <span id="opd-mpesa-status" class="ms-2 text-success fw-bold" style="display:none;"><i class="bi bi-check-circle"></i> Payment confirmed</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">Record Visit</button>
                            <a href="{{ route('hms.opd.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('hms.partials.mpesa-payment-modal')
@endsection

@push('scripts')
<script>
(function () {
    var currency = '{{ \App\Models\SystemSetting::get("currency_symbol", "KSh ") }}';

    function currentFee() {
        return parseFloat(document.getElementById('opd_consultation_fee').value) || 0;
    }

    function updateFeeDisplay() {
        document.getElementById('opd-fee-display').textContent = currency + currentFee().toFixed(2);
    }

    document.getElementById('opd_doctor_id').addEventListener('change', function () {
        var sel = this.selectedOptions[0];
        if (sel && sel.dataset.fee) {
            document.getElementById('opd_consultation_fee').value = sel.dataset.fee;
            updateFeeDisplay();
        }
    });

    document.getElementById('opd_consultation_fee').addEventListener('input', updateFeeDisplay);

    function getPatient() {
        var sel = document.getElementById('opd_patient_id');
        return sel.selectedOptions.length ? sel.selectedOptions[0] : null;
    }

    function fetchCoverage() {
        var patient = getPatient();
        var badge = document.getElementById('opd-coverage-badge');
        if (!patient || !patient.value) { badge.textContent = ''; return; }
        var csrf = document.querySelector('meta[name="csrf-token"]');
        fetch('{{ route("hms.mpesa.coverage") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf ? csrf.getAttribute('content') : '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ patient_id: patient.value, fee_type: 'consultation_fee' })
        }).then(function (r) { return r.json(); }).then(function (resp) {
            badge.textContent = resp.covered ? 'SHA covered for consultation' : 'Not covered under SHA for this service';
            badge.className = 'form-text ' + (resp.covered ? 'text-success' : 'text-danger');
        });
    }

    document.getElementById('opd_patient_id').addEventListener('change', fetchCoverage);

    document.querySelectorAll('input[name="opd_billing"]').forEach(function (r) {
        r.addEventListener('change', function () {
            document.getElementById('opd-mpesa-action').style.display = (this.value === 'mpesa') ? 'block' : 'none';
            document.getElementById('opd-sha-error').style.display = 'none';
        });
    });

    window.startOpdMpesaPayment = function () {
        var patient = getPatient();
        var fee = currentFee();
        if (!patient || !patient.value) { alert('Select a patient first.'); return; }
        if (fee <= 0) { alert('This consultation has no charge; use cash or SHA billing.'); return; }

        openMpesaModal({
            patientId: patient.value,
            phone: patient.dataset.phone || '',
            feeType: 'consultation_fee',
            feeLabel: 'Consultation Fee',
            itemName: 'Consultation - ' + (document.getElementById('opd_doctor_id').selectedOptions[0] ? document.getElementById('opd_doctor_id').selectedOptions[0].textContent : 'Doctor'),
            amount: fee,
            sourceType: 'opd_visit',
            onSuccess: function (config, resp) {
                document.getElementById('opd-payment-id').value = config.paymentId;
                document.getElementById('opd-mpesa-status').style.display = 'inline';
            }
        });
    };

    document.getElementById('opd-visit-form').addEventListener('submit', function (e) {
        var fee = currentFee();
        var checked = document.querySelector('input[name="opd_billing"]:checked');
        if (fee > 0 && !checked) { e.preventDefault(); alert('Choose a billing option for the consultation: SHA, M-Pesa or Cash.'); return; }
        if (checked && checked.value === 'mpesa' && !document.getElementById('opd-payment-id').value) {
            e.preventDefault(); alert('Complete the M-Pesa payment for the consultation before recording the visit.'); return;
        }
        document.getElementById('opd-billing-mode').value = checked ? checked.value : (fee > 0 ? 'cash' : 'none');
    });

    updateFeeDisplay();
    fetchCoverage();
})();
</script>
@endpush