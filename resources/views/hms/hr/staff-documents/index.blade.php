@extends('layouts.app')
@section('title', 'Staff Documents')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Staff Documents</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>#</th><th>Employee</th><th>Type</th><th>Title</th><th>Uploaded By</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($documents as $doc)
                <tr>
                    <td>{{ $doc->id }}</td>
                    <td>{{ $doc->employee->first_name ?? '' }} {{ $doc->employee->last_name ?? '' }}</td>
                    <td>{{ $doc->document_type }}</td>
                    <td>{{ $doc->title }}</td>
                    <td>{{ $doc->uploadedByUser->name ?? '' }}</td>
                    <td>{{ $doc->created_at?->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6">No documents found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $documents->links() }}
</div>
@endsection
