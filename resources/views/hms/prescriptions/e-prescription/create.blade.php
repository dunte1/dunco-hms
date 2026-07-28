@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-file-prescription text-primary mr-2"></i>
                Create E-Prescription
            </h2>
            <p class="text-muted mb-0">Create a digital prescription with signature</p>
        </div>
    </div>

    <form id="ePrescriptionForm" action="{{ route('hms.prescriptions.e-prescription.store') }}" method="POST">
        @csrf
        <input type="hidden" name="template_id" value="{{ $template->id ?? '' }}">

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Patient & Doctor Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Patient <span class="text-danger">*</span></label>
                                    <select name="patient_id" class="form-control" required>
                                        <option value="">Select Patient</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}">
                                                {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_no ?? $patient->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Doctor <span class="text-danger">*</span></label>
                                    <select name="doctor_id" class="form-control" required>
                                        <option value="">Select Doctor</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">
                                                {{ $doctor->first_name }} {{ $doctor->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prescription Date <span class="text-danger">*</span></label>
                                    <input type="date" name="prescription_date" class="form-control" 
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Medical Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Symptoms</label>
                            <textarea name="symptoms" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Diagnosis</label>
                            <textarea name="diagnosis" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Medicines</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addMedicine">
                            <i class="fas fa-plus mr-1"></i> Add Medicine
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="medicinesContainer">
                            <div class="medicine-row border-bottom pb-3 mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Medicine <span class="text-danger">*</span></label>
                                            <select name="medicines[0][medicine_id]" class="form-control" required>
                                                <option value="">Select Medicine</option>
                                                @foreach($medicines as $medicine)
                                                    <option value="{{ $medicine->id }}">
                                                        {{ $medicine->name }} ({{ $medicine->dosage_form }}, {{ $medicine->strength }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Dosage <span class="text-danger">*</span></label>
                                            <input type="text" name="medicines[0][dosage]" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Frequency <span class="text-danger">*</span></label>
                                            <input type="text" name="medicines[0][frequency]" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                            <input type="number" name="medicines[0][quantity]" class="form-control" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                            <input type="number" name="medicines[0][duration_days]" class="form-control" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Instructions</label>
                                            <input type="text" name="medicines[0][instructions]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Digital Signature</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Signature <span class="text-danger">*</span></label>
                            <canvas id="signatureCanvas" width="600" height="200" 
                                    class="border rounded" style="cursor: crosshair;"></canvas>
                            <input type="hidden" name="digital_signature" id="digitalSignature">
                        </div>
                        <button type="button" class="btn btn-secondary" id="clearSignature">
                            <i class="fas fa-eraser mr-2"></i> Clear
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-save mr-2"></i> Save & Sign
                        </button>
                        <a href="{{ route('hms.pharmacy.prescriptions.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let medicineCount = 1;
let isDrawing = false;

// Signature canvas
const canvas = document.getElementById('signatureCanvas');
const ctx = canvas.getContext('2d');
ctx.strokeStyle = '#000';
ctx.lineWidth = 2;

canvas.addEventListener('mousedown', startDrawing);
canvas.addEventListener('mousemove', draw);
canvas.addEventListener('mouseup', stopDrawing);
canvas.addEventListener('mouseout', stopDrawing);

function startDrawing(e) {
    isDrawing = true;
    const rect = canvas.getBoundingClientRect();
    ctx.beginPath();
    ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
}

function draw(e) {
    if (!isDrawing) return;
    const rect = canvas.getBoundingClientRect();
    ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
    ctx.stroke();
}

function stopDrawing() {
    if (isDrawing) {
        isDrawing = false;
        document.getElementById('digitalSignature').value = canvas.toDataURL();
    }
}

document.getElementById('clearSignature').addEventListener('click', function() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById('digitalSignature').value = '';
});

// Add medicine row
document.getElementById('addMedicine').addEventListener('click', function() {
    const container = document.getElementById('medicinesContainer');
    const newRow = document.createElement('div');
    newRow.className = 'medicine-row border-bottom pb-3 mb-3';
    newRow.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Medicine <span class="text-danger">*</span></label>
                    <select name="medicines[${medicineCount}][medicine_id]" class="form-control" required>
                        <option value="">Select Medicine</option>
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}">
                                {{ $medicine->name }} ({{ $medicine->dosage_form }}, {{ $medicine->strength }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Dosage <span class="text-danger">*</span></label>
                    <input type="text" name="medicines[${medicineCount}][dosage]" class="form-control" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Frequency <span class="text-danger">*</span></label>
                    <input type="text" name="medicines[${medicineCount}][frequency]" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="medicines[${medicineCount}][quantity]" class="form-control" min="1" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                    <input type="number" name="medicines[${medicineCount}][duration_days]" class="form-control" min="1" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Instructions</label>
                    <input type="text" name="medicines[${medicineCount}][instructions]" class="form-control">
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-danger remove-medicine">
            <i class="fas fa-trash mr-1"></i> Remove
        </button>
    `;
    container.appendChild(newRow);
    medicineCount++;
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-medicine')) {
        e.target.closest('.medicine-row').remove();
    }
});

// Form submission
document.getElementById('ePrescriptionForm').addEventListener('submit', function(e) {
    if (!document.getElementById('digitalSignature').value) {
        e.preventDefault();
        alert('Please provide a digital signature');
        return false;
    }
});
</script>
@endpush
@endsection

