@extends('layouts.app')
@section('title', 'Staff Licences')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Staff Licences</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>#</th><th>Employee</th><th>Type</th><th>Licence #</th><th>Issuing Body</th><th>Expiry</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($licences as $licence)
                <tr>
                    <td>{{ $licence->id }}</td>
                    <td>{{ $licence->employee->first_name ?? '' }} {{ $licence->employee->last_name ?? '' }}</td>
                    <td>{{ $licence->licence_type }}</td>
                    <td>{{ $licence->licence_number }}</td>
                    <td>{{ $licence->issuing_body }}</td>
                    <td>{{ $licence->expiry_date?->format('d M Y') }}</td>
                    <td><span class="badge bg-{{ $licence->status === 'active' ? 'success' : 'danger' }}">{{ $licence->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="7">No licences found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $licences->links() }}
</div>
@endsection
