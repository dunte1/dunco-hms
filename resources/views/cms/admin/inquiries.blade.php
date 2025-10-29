@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-envelope-open-text mr-2"></i> Contact Inquiries</h4>
                    <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    @if($enquiries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enquiries as $enquiry)
                                        <tr>
                                            <td>{{ $enquiry->name ?? 'N/A' }}</td>
                                            <td>{{ $enquiry->email ?? 'N/A' }}</td>
                                            <td>{{ $enquiry->phone ?? 'N/A' }}</td>
                                            <td>{{ $enquiry->subject ?? 'N/A' }}</td>
                                                    <td>{{ \Illuminate\Support\Str::limit($enquiry->message ?? 'N/A', 50) }}</td>
                                            <td>{{ $enquiry->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewModal{{ $enquiry->id }}">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal{{ $enquiry->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Enquiry Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Name:</strong> {{ $enquiry->name ?? 'N/A' }}</p>
                                                        <p><strong>Email:</strong> {{ $enquiry->email ?? 'N/A' }}</p>
                                                        <p><strong>Phone:</strong> {{ $enquiry->phone ?? 'N/A' }}</p>
                                                        <p><strong>Subject:</strong> {{ $enquiry->subject ?? 'N/A' }}</p>
                                                        <p><strong>Message:</strong></p>
                                                        <p>{{ $enquiry->message ?? 'N/A' }}</p>
                                                        <p><strong>Date:</strong> {{ $enquiry->created_at->format('Y-m-d H:i:s') }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $enquiries->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle mr-2"></i> No contact inquiries found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

