@extends('admin.layouts.app')

@section('title', 'Salary Expense Analysis')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-white mb-0 fw-bold"><i class="fas fa-dollar-sign me-3"></i>Salary Expense Analysis - {{ $year }}</h2>
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
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Monthly Salary Expenses</h5>
                    <canvas id="monthlyChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Total: {{ number_format(array_sum($monthlyExpenses), 2) }}</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyExpenses as $month => $amount)
                                    <tr>
                                        <td>{{ date('M', mktime(0, 0, 0, $month, 1)) }}</td>
                                        <td class="text-end"><strong>{{ number_format($amount, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Department-wise Expenses</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Department</th>
                                    <th class="text-end">Total Salary Expense</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departmentExpenses as $dept)
                                    <tr>
                                        <td>{{ $dept->department }}</td>
                                        <td class="text-end"><strong>{{ number_format($dept->total, 2) }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5">
                                            <p class="text-muted">No data available</p>
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
    const ctx = document.getElementById('monthlyChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {{ json_encode(array_map(fn($m) => date('M', mktime(0, 0, 0, $m, 1)), array_keys($monthlyExpenses))) }},
                datasets: [{
                    label: 'Salary Expense',
                    data: {{ json_encode(array_values($monthlyExpenses)) }},
                    backgroundColor: '#f97316'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection

