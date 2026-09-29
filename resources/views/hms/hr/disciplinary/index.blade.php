@extends('layouts.app')
@section('title', 'Disciplinary Records')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Disciplinary Records</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>#</th><th>Employee</th><th>Date</th><th>Category</th><th>Severity</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($records as $record)
                <tr>
                    <td>{{ $record->id }}</td>
                    <td>{{ $record->employee->first_name ?? '' }} {{ $record->employee->last_name ?? '' }}</td>
                    <td>{{ $record->incident_date?->format('d M Y') }}</td>
                    <td>{{ $record->category }}</td>
                    <td>{{ $record->severity }}</td>
                    <td><span class="badge bg-{{ $record->status === 'resolved' ? 'success' : ($record->status === 'open' ? 'warning' : 'secondary') }}">{{ $record->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $records->links() }}
</div>
@endsection
