@extends('admin.layouts.app')

@section('title', 'Attrition Report')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-white mb-0 fw-bold"><i class="fas fa-user-minus me-3"></i>Attrition Report - {{ $year }}</h2>
                    <form method="GET" class="d-inline">
                        <select name="year" class="form-select d-inline-block" style="width: auto;" onchange="this.form.submit()">
                            @for($i = now()->year; $i >= now()->year - 5; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Total Attrition: {{ $attrition->count() }}</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th class="text-end">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departmentAttrition as $dept)
                                    <tr>
                                        <td>{{ $dept->department }}</td>
                                        <td class="text-end"><strong>{{ $dept->count }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <canvas id="attritionChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">Employee</th>
                                    <th class="px-4 py-3">Department</th>
                                    <th class="px-4 py-3">Termination Date</th>
                                    <th class="px-4 py-3">Position</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attrition as $employee)
                                    <tr>
                                        <td class="px-4 py-3">{{ $employee->full_name }}</td>
                                        <td class="px-4 py-3">{{ $employee->department->name ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $employee->termination_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">{{ $employee->position }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <p class="text-muted">No terminations recorded for {{ $year }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('attritionChart');
    if (ctx && @json($departmentAttrition->count() > 0)) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($departmentAttrition->pluck('department')),
                datasets: [{
                    data: @json($departmentAttrition->pluck('count')),
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
});
</script>
@endpush
@endsection

