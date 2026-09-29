@extends('layouts.app')
@section('title', 'Payroll Exports')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Payroll Exports</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>#</th><th>Month</th><th>Year</th><th>Gross</th><th>Deductions</th><th>Net</th><th>Employees</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($exports as $export)
                <tr>
                    <td>{{ $export->id }}</td>
                    <td>{{ $export->export_month }}</td>
                    <td>{{ $export->export_year }}</td>
                    <td>{{ number_format($export->total_gross, 2) }}</td>
                    <td>{{ number_format($export->total_deductions, 2) }}</td>
                    <td>{{ number_format($export->total_net, 2) }}</td>
                    <td>{{ $export->employee_count }}</td>
                    <td><span class="badge bg-{{ $export->status === 'finalized' ? 'success' : 'info' }}">{{ $export->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="8">No exports found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $exports->links() }}
</div>
@endsection
