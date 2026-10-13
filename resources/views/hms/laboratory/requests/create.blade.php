<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.laboratory.requests.index') }}" class="hover:text-purple-600">Laboratory</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>New Lab Request</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-flask text-purple-600 mr-3"></i>
                    New Lab Request
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Order laboratory tests for a patient</p>
            </div>

            @if(session('success') || session('status') || session('error') || $errors->any())
                <div class="mb-4">
                    @if(session('success') || session('status'))
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">{{ session('success') ?: session('status') }}</div>
                    @endif
                    @if(session('error') || $errors->any())
                        <div class="mt-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                            @if(session('error'))<p class="font-medium">{{ session('error') }}</p>@endif
                            @if($errors->any())
                                <ul class="mt-1 list-disc list-inside text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('hms.laboratory.requests.store') }}" method="POST" id="lab-request-form">
                    @csrf
                    <input type="hidden" name="billing_mode" id="billing_mode" value="">
                    <input type="hidden" name="opd_visit_id" id="opd_visit_id" value="{{ old('opd_visit_id', request('opd_visit_id')) }}">
                    <input type="hidden" name="payment_id" id="payment_id" value="">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient <span class="text-red-500">*</span></label>
                            <select name="patient_id" id="lab_patient_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" data-phone="{{ $patient->phone }}" @selected(old('patient_id', request('patient_id')) == $patient->id)>{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                @endforeach
                            </select>
                            <div id="lab-coverage-badge" class="mt-1 text-xs"></div>
                            @error('patient_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doctor</label>
                            <select name="doctor_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                                @endforeach
                            </select>
                            @error('doctor_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Request Date <span class="text-red-500">*</span></label>
                            <input type="date" name="request_date" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500" value="{{ old('request_date', date('Y-m-d')) }}" required>
                            @error('request_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Clinical Notes</label>
                        <textarea name="clinical_notes" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">{{ old('clinical_notes') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Select Lab Tests <span class="text-red-500">*</span></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @forelse($labTests as $test)
                                <label class="flex items-start gap-2 p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                    <input class="form-check-input lab-test-check mt-1" type="checkbox" name="lab_tests[]" value="{{ $test->id }}" id="test_{{ $test->id }}" data-price="{{ $test->price }}" data-name="{{ $test->test_name }}">
                                    <span class="text-sm text-gray-800 dark:text-gray-200">
                                        {{ $test->test_name }}
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($test->price, 2) }}</span>
                                    </span>
                                </label>
                            @empty
                                <p class="col-span-full text-sm text-gray-500">No lab tests configured. Add tests under Laboratory → Tests first.</p>
                            @endforelse
                        </div>
                        @error('lab_tests')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6 p-4 border border-emerald-200 dark:border-emerald-800 rounded-xl bg-emerald-50 dark:bg-emerald-900/10">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                            <span class="font-semibold text-gray-800 dark:text-gray-200">Billing</span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total:</span>
                                <span class="text-xl font-bold text-emerald-700 dark:text-emerald-400" id="lab-total">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}0.00</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4">
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                <input class="form-check-input" type="radio" name="lab_billing" id="billing_sha" value="sha">
                                SHA (covered)
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                <input class="form-check-input" type="radio" name="lab_billing" id="billing_mpesa" value="mpesa">
                                M-Pesa
                            </label>
                        </div>
                        <div id="lab-mpesa-action" class="mt-3" style="display:none;">
                            <button type="button" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm" onclick="startLabMpesaPayment()">Pay with M-Pesa</button>
                            <span id="lab-mpesa-status" class="ml-2 text-sm font-bold text-emerald-700 dark:text-emerald-400" style="display:none;"><i class="fa fa-check-circle"></i> Payment confirmed</span>
                        </div>
                        <div id="lab-sha-error" class="mt-3 text-sm text-amber-700 dark:text-amber-300" style="display:none;">
                            This patient is not covered for lab tests under SHA. Please choose M-Pesa payment or confirm the SHA status before continuing.
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium" id="lab-submit-btn">
                            <i class="fa fa-save mr-2"></i> Create Request
                        </button>
                        <a href="{{ route('hms.laboratory.requests.index') }}" class="px-6 py-2.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('hms.partials.mpesa-payment-modal')

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
                badge.className = 'mt-1 text-xs ' + (resp.covered ? 'text-emerald-600' : 'text-red-600');
            }).catch(function () {});
        }

        document.addEventListener('change', function (e) {
            if (e.target.classList && e.target.classList.contains('lab-test-check')) updateTotal();
        });

        document.getElementById('lab_patient_id').addEventListener('change', fetchCoverage);

        document.querySelectorAll('input[name="lab_billing"]').forEach(function (r) {
            r.addEventListener('change', function () {
                var shaErr = document.getElementById('lab-sha-error');
                var mpesaAction = document.getElementById('lab-mpesa-action');
                if (this.value === 'sha') {
                    mpesaAction.style.display = 'none';
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
</x-app-layout>
