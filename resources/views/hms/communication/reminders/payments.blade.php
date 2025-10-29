@extends('admin.layouts.app')

@section('title', 'Payment Reminders')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-dollar-sign me-3"></i>Payment Reminders
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.reminders.index') }}" class="text-white-50">Reminders</a></li>
                                <li class="breadcrumb-item text-white active">Payments</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success px-3 py-2 me-3">
                            <i class="fas fa-dollar-sign me-1"></i>
                        </span>
                        Pending & Partial Payments
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Invoice #</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Patient</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Total Amount</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Paid</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Balance</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Due Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Status</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Send Reminder</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">#{{ $invoice->invoice_number }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $invoice->patient->full_name ?? 'N/A' }}</h6>
                                                        <small class="text-muted">{{ $invoice->patient->patient_no ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">${{ number_format($invoice->total_amount, 2) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-success">${{ number_format($invoice->paid_amount ?? 0, 2) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-danger">${{ number_format($invoice->balance_amount ?? $invoice->total_amount, 2) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="fw-medium text-dark">
                                                    <i class="far fa-calendar me-1 text-success"></i>
                                                    {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}
                                                </div>
                                                <small class="text-muted">
                                                    @php
                                                        $daysPastDue = \Carbon\Carbon::parse($invoice->due_date)->diffInDays(now(), false);
                                                    @endphp
                                                    @if($daysPastDue > 0)
                                                        <span class="text-danger">{{ $daysPastDue }} days overdue</span>
                                                    @else
                                                        {{ abs($daysPastDue) }} days remaining
                                                    @endif
                                                </small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $invoice->status === 'paid' ? '#d1fae5' : ($invoice->status === 'partial' ? '#fef3c7' : '#fee2e2') }}; 
                                                             color: {{ $invoice->status === 'paid' ? '#065f46' : ($invoice->status === 'partial' ? '#78350f' : '#991b1b') }};">
                                                    <i class="fas fa-{{ $invoice->status === 'paid' ? 'check' : ($invoice->status === 'partial' ? 'clock' : 'exclamation') }}-circle me-1"></i>
                                                    {{ ucfirst($invoice->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Send Email">
                                                        <i class="fas fa-envelope"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Send SMS">
                                                        <i class="fas fa-sms"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($invoices->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $invoices->firstItem() }}</strong> to <strong>{{ $invoices->lastItem() }}</strong> of <strong>{{ $invoices->total() }}</strong> entries
                            </div>
                            <div>{{ $invoices->links() }}</div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                                    <i class="fas fa-dollar-sign" style="font-size: 3rem; color: #10b981;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Pending Payments</h4>
                            <p class="text-muted mb-4">All invoices have been paid in full.</p>
                            <a href="{{ route('hms.billing.invoices.index') }}" class="btn btn-primary btn-lg px-5" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                <i class="fas fa-file-invoice me-2"></i>View Invoices
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .btn-outline-primary:hover {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    .btn-outline-success:hover {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }

    * {
        transition: all 0.3s ease;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endsection
