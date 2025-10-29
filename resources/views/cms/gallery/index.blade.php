@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-images mr-2"></i> Gallery Management</h4>
                    <div>
                        <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary mr-2">
                            <i class="fa fa-arrow-left mr-1"></i> Back
                        </a>
                        <a href="{{ route('cms.gallery.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus mr-1"></i> Add Item
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Filters -->
                    <form method="GET" action="{{ route('cms.gallery.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input 
                                    type="text" 
                                    name="search" 
                                    class="form-control" 
                                    placeholder="Search items..." 
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="category" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Image</option>
                                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-search mr-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Gallery Items Grid -->
                    @if($items->count() > 0)
                        <div class="row">
                            @foreach($items as $item)
                                <div class="col-md-3 mb-4">
                                    <div class="card h-100">
                                        @if($item->file_path || $item->image_path)
                                            <img 
                                                src="{{ asset('storage/' . ($item->file_path ?? $item->image_path)) }}" 
                                                alt="{{ $item->title }}" 
                                                class="card-img-top" 
                                                style="height: 200px; object-fit: cover;">
                                        @elseif($item->thumbnail_path)
                                            <img 
                                                src="{{ asset('storage/' . $item->thumbnail_path) }}" 
                                                alt="{{ $item->title }}" 
                                                class="card-img-top" 
                                                style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                                <i class="fa fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $item->title }}</h6>
                                            @if($item->category)
                                                <span class="badge badge-info mb-2">{{ $item->category->name }}</span>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                @if($item->type ?? 'image' == 'image')
                                                    <small class="text-muted"><i class="fa fa-image"></i> Image</small>
                                                @else
                                                    <small class="text-muted"><i class="fa fa-video"></i> Video</small>
                                                @endif
                                                <div>
                                                    @if($item->is_featured)
                                                        <i class="fa fa-star text-warning" title="Featured"></i>
                                                    @endif
                                                    @if($item->is_active)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-secondary">Inactive</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="card-footer">
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('cms.gallery.show', $item) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('cms.gallery.edit', $item) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('cms.gallery.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $items->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle mr-2"></i> No gallery items found. 
                            <a href="{{ route('cms.gallery.create') }}">Add your first item</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

