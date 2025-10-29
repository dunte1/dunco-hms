@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
+    <div class="row">
+        <div class="col-12">
+            <div class="card">
+                <div class="card-header">
+                    <h3 class="card-title">New OPD Visit</h3>
+                </div>
+                <div class="card-body">
+                    <form action="{{ route('hms.opd.store') }}" method="POST">
+                        @csrf
+                        <div class="row">
+                            <div class="col-md-6">
+                                <div class="mb-3">
+                                    <label class="form-label">Patient</label>
+                                    <select name="patient_id" class="form-control" required>
+                                        <option value="">Select Patient</option>
+                                        @foreach($patients as $patient)
+                                        <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
+                                        @endforeach
+                                    </select>
+                                </div>
+                            </div>
+                            <div class="col-md-6">
+                                <div class="mb-3">
+                                    <label class="form-label">Doctor</label>
+                                    <select name="doctor_id" class="form-control">
+                                        <option value="">Select Doctor</option>
+                                        @foreach($doctors as $doctor)
+                                        <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
+                                        @endforeach
+                                    </select>
+                                </div>
+                            </div>
+                        </div>
+                        <div class="row">
+                            <div class="col-md-6">
+                                <div class="mb-3">
+                                    <label class="form-label">Visit Date</label>
+                                    <input type="datetime-local" name="visit_date" class="form-control" required>
+                                </div>
+                            </div>
+                            <div class="col-md-6">
+                                <div class="mb-3">
+                                    <label class="form-label">Visit Type</label>
+                                    <select name="visit_type" class="form-control" required>
+                                        <option value="consultation">Consultation</option>
+                                        <option value="follow_up">Follow Up</option>
+                                        <option value="emergency">Emergency</option>
+                                    </select>
+                                </div>
+                            </div>
+                        </div>
+                        <div class="row">
+                            <div class="col-md-6">
+                                <div class="mb-3">
+                                    <label class="form-label">Chief Complaint</label>
+                                    <textarea name="chief_complaint" class="form-control" rows="3"></textarea>
+                                </div>
+                            </div>
+                            <div class="col-md-6">
+                                <div class="mb-3">
+                                    <label class="form-label">Consultation Fee</label>
+                                    <input type="number" name="consultation_fee" step="0.01" class="form-control" value="0">
+                                </div>
+                            </div>
+                        </div>
+                        <div class="row">
+                            <div class="col-md-6">
+                                <div class="mb-3">
+                                    <label class="form-label">Diagnosis</label>
+                                    <textarea name="diagnosis" class="form-control" rows="3"></textarea>
+                                </div>
+                            </div>
+                            <div class="col-md-6">
+                                <div class="mb-3">
+                                    <label class="form-label">Prescription</label>
+                                    <textarea name="prescription" class="form-control" rows="3"></textarea>
+                                </div>
+                            </div>
+                        </div>
+                        <div class="d-flex gap-2">
+                            <button type="submit" class="btn btn-primary">Record Visit</button>
+                            <a href="{{ route('hms.opd.index') }}" class="btn btn-secondary">Cancel</a>
+                        </div>
+                    </form>
+                </div>
+            </div>
+        </div>
+    </div>
+</div>
@endsection
