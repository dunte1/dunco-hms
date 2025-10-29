@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-eye mr-2"></i> Gallery Item Details</h4>
                    <div>
                        <a href="{{ route('cms.gallery.edit', $item) }}" class="btn btn-warning mr-2">
                            <i class="fa fa-edit mr-1"></i> Edit
                        </a>
                        <a href="{{ route('cms.gallery.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left mr-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            @if($item->file_path || $item->image_path)
                                <div class="mb-4">
                                    <img 
                                        src="{{ asset('storage/' . ($item->file_path ?? $item->image_path)) }}" 
                                        alt="{{ $item->title }}" 
                                        class="img-fluid rounded">
                                </div>
                            @elseif($item->video_url)
                                <div class="mb-4">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="{{ $item->video_url }}" allowfullscreen></iframe>
                                    </div>
                                </div>
                            @endif

                            <h3>{{ $item->title }}</h3>
                            
                            @if($item->description)
                                <p class="text-muted">{{ $item->description }}</p>
                            @endif

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Category:</strong> 
                                        @if($item->category)
                                            <span class="badge badge-info">{{ $item->category->name }}</span>
                                        @else
                                            <span class="text-muted">Uncategorized</span>
                                        @endif
                                    </p>
                                    <p><strong>Type:</strong> 
                                        @if(($item->type ?? 'image') == 'image')
                                            <span class="badge badge-primary">Image</span>
                                        @else
                                            <span class="badge badge-danger">Video</span>
                                        @endif
                                    </p>
                                    <p><strong>Status:</strong> 
                                        @if($item->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Featured:</strong> 
                                        @if($item->is_featured)
                                            <i class="fa fa-star text-warning"></i> Yes
                                        @else
                                            <i class="fa fa-star text-muted"></i> No
                                        @endif
                                    </p>
                                    <p><strong>Sort Order:</strong> {{ $item->sort_order ?? 0 }}</p>
                                    <p><strong>Created:</strong> {{ $item->created_at->format('Y-m-d H:i:s') }}</p>
                                    <p><strong>Updated:</strong> {{ $item->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

