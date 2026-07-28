@extends('admin.layouts.app')

@section('title', 'HR Reports')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                <h2 class="text-white mb-0 fw-bold"><i class="fas fa-chart-bar me-3"></i>HR Reports & Analytics</h2>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                        <i class="fas fa-users text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Employee List</h5>
                    <p class="text-muted mb-3">Generate comprehensive employee listings by department or status</p>
                    <a href="{{ route('hms.hr.reports.employee-list') }}" class="btn btn-primary w-100">
                        <i class="fas fa-arrow-right me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="fas fa-calendar-times text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Leave Report</h5>
                    <p class="text-muted mb-3">Track and analyze employee leave requests and patterns</p>
                    <a href="{{ route('hms.hr.reports.leave') }}" class="btn btn-warning w-100">
                        <i class="fas fa-arrow-right me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-clock text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Attendance Report</h5>
                    <p class="text-muted mb-3">Monitor employee attendance and punctuality</p>
                    <a href="{{ route('hms.hr.reports.attendance') }}" class="btn btn-success w-100">
                        <i class="fas fa-arrow-right me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                        <i class="fas fa-money-bill-wave text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Payroll Summary</h5>
                    <p class="text-muted mb-3">Detailed payroll analysis and summaries</p>
                    <a href="{{ route('hms.hr.reports.payroll-summary') }}" class="btn btn-pink w-100">
                        <i class="fas fa-arrow-right me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                        <i class="fas fa-chart-line text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Headcount Trends</h5>
                    <p class="text-muted mb-3">Analyze employee growth and headcount trends over time</p>
                    <a href="{{ route('hms.hr.reports.headcount-trends') }}" class="btn btn-info w-100">
                        <i class="fas fa-arrow-right me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                        <i class="fas fa-user-minus text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Attrition Report</h5>
                    <p class="text-muted mb-3">Track employee turnover and attrition rates</p>
                    <a href="{{ route('hms.hr.reports.attrition') }}" class="btn btn-danger w-100">
                        <i class="fas fa-arrow-right me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                        <i class="fas fa-dollar-sign text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Salary Expense Analysis</h5>
                    <p class="text-muted mb-3">Analyze salary expenses by department and month</p>
                    <a href="{{ route('hms.hr.reports.salary-expense') }}" class="btn btn-orange w-100">
                        <i class="fas fa-arrow-right me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-graduation-cap text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Training Participation</h5>
                    <p class="text-muted mb-3">Track training program participation and completion rates</p>
                    <a href="{{ route('hms.hr.reports.training-participation') }}" class="btn btn-success w-100">
                        <i class="fas fa-arrow-right me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

