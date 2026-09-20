@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">New Lab Request</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('hms.laboratory.requests.store') }}" method="POST" id="lab-request-form">
                        @csrf
                        <input type="hidden" name="billing_mode" id="billing_mode" value="">
                        <input type="hidden" name="payment_id" id="payment_id" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Patient</label>
                                    <select name="patient_id" id="lab_patient_id" class="form-control" required>
                                        <option value="">Select Patient</option>
                                        @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" data-phone="{{ $patient->phone }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="lab-coverage-badge" class="form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Doctor</label>
                                    <select name="doctor_id" class="form-control">
                                        <option value="">Select Doctor</option>
                                        @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Request Date</label>
                                    <input type="date" name="request_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Clinical Notes</label>
                            <textarea name="clinical_notes" class="form-control" rows="3"></textarea>
                        </div>

                        <h5>Select Lab Tests</h5>
                        <div class="row">
                            @foreach($labTests as $test)
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input lab-test-check" type="checkbox" name="lab_tests[]" value="{{ $test->id }}" id="test_{{ $test->id }}" data-price="{{ $test->price }}" data-name="{{ $test->test_name }}">
                                    <label class="form-check-label" for="test_{{ $test->id }}">
                                        {{ $test->test_name }} - {{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($test->price, 2) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Payment / Coverage section --}}
                        <div class="card border-success mt-3">
                            <div class="card-body">
                                <h5 class="card-title">Billing</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold">Total Lab Charges</span>
                                            <span class="fs-4 fw-bold text-success" id="lab-total">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}0.00</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="lab_billing" id="billing_sha" value="sha">
                                            <label class="form-check-label" for="billing_sha">SHA (covered)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="lab_billing" id="billing_mpesa" value="mpesa">
                                            <label class="form-check-label" for="billing_mpesa">M-Pesa</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="lab-mpesa-action" style="display:none;">
                                    <button type="button" class="btn btn-success" onclick="startLabMpesaPayment()">Pay with M-Pesa</button>
                                    <span id="lab-mpesa-status" class="ms-2 text-success fw-bold" style="display:none;"><i class="bi bi-check-circle"></i> Payment confirmed</span>
                                </div>
                                <div id="lab-sha-error" class="alert alert-warning mt-2 mb-0" style="display:none;font-size:13px;">
                                    This patient is not covered for lab tests under SHA. Please choose M-Pesa payment or confirm the SHA status before continuing.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary" id="lab-submit-btn">Create Request</button>
                            <a href="{{ route('hms.laboratory.requests.index') }}" class="btn btn-secondary">Cancel</a>
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

    function selectedTests() {
        return Array.prototype.slice.call(document.querySelectorAll('.lab-test-check:checked')).map(function (c) {
            return { id: c.value, price: parseFloat(c.dataset.price) || 0, name: c.dataset.name };
        });
    }

    function updateTotal() {
        var total = selectedTests().reduce(function (s, t) { return s + t.price; }, 0);
        document.getElementById('lab-total').textContent = currency + total.toFixed(2);
        return total;
    }

    function getPatient() {
        var sel = document.getElementById('lab_patient_id');
        return sel.selectedOptions.length ? sel.selectedOptions[0] : null;
    }

    function fetchCoverage() {
        var patient = getPatient();
        var badge = document.getElementById('lab-coverage-badge');
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
            body: JSON.stringify({ patient_id: patient.value, fee_type: 'lab_test' })
        }).then(function (r) { return r.json(); }).then(function (resp) {
            badge.textContent = resp.covered ? 'SHA covered for lab tests' : 'Not covered under SHA for this service';
            badge.className = 'form-text ' + (resp.covered ? 'text-success' : 'text-danger');
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('lab-test-check')) updateTotal();
    });

    document.getElementById('lab_patient_id').addEventListener('change', fetchCoverage);

    document.querySelectorAll('input[name="lab_billing"]').forEach(function (r) {
        r.addEventListener('change', function () {
            var shaErr = document.getElementById('lab-sha-error');
            var mpesaAction = document.getElementById('lab-mpesa-action');
            if (this.value === 'sha') {
                mpesaAction.style.display = 'none';
                // Confirmed-payment not required for SHA billing
                document.getElementById('payment_id').value = '';
                shaErr.style.display = 'none';
            } else {
                mpesaAction.style.display = 'block';
            }
        });
    });

    window.startLabMpesaPayment = function () {
        var patient = getPatient();
        var tests = selectedTests();
        var total = updateTotal();

        if (!patient || !patient.value) { alert('Select a patient first.'); return; }
        if (!tests.length) { alert('Select at least one lab test.'); return; }
        if (total <= 0) { alert('Total amount is zero.'); return; }

        openMpesaModal({
            patientId: patient.value,
            phone: patient.dataset.phone || '',
            feeType: 'lab_test',
            feeLabel: 'Lab Test Fee',
            itemName: tests.map(function (t) { return t.name; }).join(', '),
            amount: total,
            sourceType: 'lab_request',
            onSuccess: function (config, resp) {
                document.getElementById('payment_id').value = config.paymentId;
                document.getElementById('billing_mode').value = 'mpesa';
                document.getElementById('lab-mpesa-status').style.display = 'inline';
                document.getElementById('lab-submit-btn').innerHTML = 'Create Request (' + currency + Number(resp.amount).toFixed(2) + ')';
            }
        });
    };

    document.getElementById('lab-request-form').addEventListener('submit', function (e) {
        var checked = document.querySelector('input[name="lab_billing"]:checked');
        if (!checked) { e.preventDefault(); alert('Choose a billing option: SHA or M-Pesa.'); return; }
        if (checked.value === 'sha') {
            document.getElementById('billing_mode').value = 'sha';
        } else if (!document.getElementById('payment_id').value) {
            e.preventDefault(); alert('Complete the M-Pesa payment before creating the request.'); return;
        }
    });

    fetchCoverage();
})();
</script>
@endpush