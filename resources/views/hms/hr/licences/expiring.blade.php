@extends('layouts.app')
@section('title', 'Expiring Licences')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Expiring Licences (Next 30 Days)</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>Employee</th><th>Type</th><th>Licence #</th><th>Issuing Body</th><th>Expiry Date</th></tr></thead>
            <tbody>
                @forelse($licences as $licence)
                <tr>
                    <td>{{ $licence->employee->first_name ?? '' }} {{ $licence->employee->last_name ?? '' }}</td>
                    <td>{{ $licence->licence_type }}</td>
                    <td>{{ $licence->licence_number }}</td>
                    <td>{{ $licence->issuing_body }}</td>
                    <td>{{ $licence->expiry_date?->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5">No licences expiring soon.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
