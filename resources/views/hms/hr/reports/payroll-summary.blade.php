@extends('admin.layouts.app')

@section('title', 'Payroll Summary')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-white mb-0 fw-bold"><i class="fas fa-money-bill-wave me-3"></i>Payroll Summary</h2>
                    <a href="{{ route('hms.hr.reports.payroll-summary', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="btn btn-light">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Gross</h6>
                    <h3 class="mb-0 text-primary">{{ number_format($summary['total_gross'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Net</h6>
                    <h3 class="mb-0 text-success">{{ number_format($summary['total_net'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Deductions</h6>
                    <h3 class="mb-0 text-danger">{{ number_format($summary['total_deductions'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Count</h6>
                    <h3 class="mb-0">{{ $summary['count'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Month</label>
                            <select name="month" class="form-select">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-select">
                                @for($i = now()->year; $i >= now()->year - 5; $i--)
                                    <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
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
                                    <th class="px-4 py-3">Pay Date</th>
                                    <th class="px-4 py-3">Gross Salary</th>
                                    <th class="px-4 py-3">Deductions</th>
                                    <th class="px-4 py-3">Net Salary</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrolls as $payroll)
                                    <tr>
                                        <td class="px-4 py-3">{{ $payroll->employee->full_name }}</td>
                                        <td class="px-4 py-3">{{ $payroll->pay_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">{{ number_format($payroll->gross_salary, 2) }}</td>
                                        <td class="px-4 py-3">{{ number_format($payroll->deductions, 2) }}</td>
                                        <td class="px-4 py-3">{{ number_format($payroll->net_salary, 2) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="badge 
                                                @if($payroll->status === 'paid') bg-success
                                                @elseif($payroll->status === 'pending') bg-warning
                                                @else bg-danger @endif">
                                                {{ ucfirst($payroll->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <p class="text-muted">No payroll records found</p>
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
@endsection

