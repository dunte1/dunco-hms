@extends('admin.layouts.app')

@section('title', 'MOH Reports Dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%); box-shadow: 0 10px 30px rgba(30, 64, 175, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-file-medical me-3"></i>MOH / Regulatory Reports
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.reports.index') }}" class="text-white-50">Reports</a></li>
                                <li class="breadcrumb-item text-white active">MOH Reports</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards Grid -->
    <div class="row g-4">
        <!-- OPD Summary -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 report-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4 text-center">
                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                        <i class="fas fa-stethoscope text-white fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">OPD Summary</h5>
                    <p class="text-muted small mb-3">Outpatient visits, diagnoses, revenue, and department breakdown.</p>
                    <a href="{{ route('hms.moh-reports.opd-summary') }}" class="btn btn-primary btn-sm px-4">
                        <i class="fas fa-chart-bar me-1"></i> Generate
                    </a>
                </div>
            </div>
        </div>

        <!-- IPD Summary -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 report-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4 text-center">
                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="fas fa-procedures text-white fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">IPD Summary</h5>
                    <p class="text-muted small mb-3">Admissions, discharges, length of stay, bed occupancy, and diagnoses.</p>
                    <a href="{{ route('hms.moh-reports.ipd-summary') }}" class="btn btn-sm px-4" style="background: #8b5cf6; color: #fff;">
                        <i class="fas fa-chart-line me-1"></i> Generate
                    </a>
                </div>
            </div>
        </div>

        <!-- Disease Surveillance -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 report-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4 text-center">
                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                        <i class="fas fa-virus text-white fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Disease Surveillance</h5>
                    <p class="text-muted small mb-3">Disease counts, trends, and patient diagnosis analytics.</p>
                    <a href="{{ route('hms.moh-reports.disease-surveillance') }}" class="btn btn-sm px-4" style="background: #ef4444; color: #fff;">
                        <i class="fas fa-viruses me-1"></i> Generate
                    </a>
                </div>
            </div>
        </div>

        <!-- Maternal Health -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 report-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4 text-center">
                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                        <i class="fas fa-baby text-white fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Maternal Health</h5>
                    <p class="text-muted small mb-3">Births, delivery types, complications, and maternal outcomes.</p>
                    <a href="{{ route('hms.moh-reports.maternal-health') }}" class="btn btn-sm px-4" style="background: #ec4899; color: #fff;">
                        <i class="fas fa-heart me-1"></i> Generate
                    </a>
                </div>
            </div>
        </div>

        <!-- Pharmacy Consumption -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 report-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4 text-center">
                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-pills text-white fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Pharmacy Consumption</h5>
                    <p class="text-muted small mb-3">Medicines dispensed, top medicines, stock value, and expiry summary.</p>
                    <a href="{{ route('hms.moh-reports.pharmacy-consumption') }}" class="btn btn-sm px-4" style="background: #10b981; color: #fff;">
                        <i class="fas fa-prescription-bottle me-1"></i> Generate
                    </a>
                </div>
            </div>
        </div>

        <!-- Revenue Collection -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 report-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4 text-center">
                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="fas fa-coins text-white fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Revenue Collection</h5>
                    <p class="text-muted small mb-3">Total collected, payment methods, outstanding balance, and by department.</p>
                    <a href="{{ route('hms.moh-reports.revenue-collection') }}" class="btn btn-sm px-4" style="background: #f59e0b; color: #fff;">
                        <i class="fas fa-money-bill-wave me-1"></i> Generate
                    </a>
                </div>
            </div>
        </div>

        <!-- Staff Attendance -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 report-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4 text-center">
                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                        <i class="fas fa-user-check text-white fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Staff Attendance</h5>
                    <p class="text-muted small mb-3">Employee attendance records, check-in/out times, and hours worked.</p>
                    <a href="{{ route('hms.moh-reports.staff-attendance') }}" class="btn btn-sm px-4" style="background: #06b6d4; color: #fff;">
                        <i class="fas fa-clipboard-list me-1"></i> Generate
                    </a>
                </div>
            </div>
        </div>

        <!-- Bed Occupancy -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 report-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4 text-center">
                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                        <i class="fas fa-bed text-white fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Bed Occupancy</h5>
                    <p class="text-muted small mb-3">Ward-wise bed occupancy rates, available beds, and utilization metrics.</p>
                    <a href="{{ route('hms.moh-reports.bed-occupancy') }}" class="btn btn-sm px-4" style="background: #6366f1; color: #fff;">
                        <i class="fas fa-bed me-1"></i> Generate
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12) !important;
    }
</style>
@endsection
