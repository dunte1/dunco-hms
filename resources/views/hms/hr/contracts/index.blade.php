@extends('layouts.app')
@section('title', 'Employee Contracts')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Employee Contracts</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>#</th><th>Employee</th><th>Type</th><th>Start</th><th>End</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($contracts as $contract)
                <tr>
                    <td>{{ $contract->id }}</td>
                    <td>{{ $contract->employee->first_name ?? '' }} {{ $contract->employee->last_name ?? '' }}</td>
                    <td>{{ $contract->contract_type }}</td>
                    <td>{{ $contract->start_date?->format('d M Y') }}</td>
                    <td>{{ $contract->end_date?->format('d M Y') ?? 'N/A' }}</td>
                    <td><span class="badge bg-{{ $contract->status === 'active' ? 'success' : 'secondary' }}">{{ $contract->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6">No contracts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $contracts->links() }}
</div>
@endsection
