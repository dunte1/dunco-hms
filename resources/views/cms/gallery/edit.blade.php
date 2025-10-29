@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-edit mr-2"></i> Edit Gallery Item</h4>
                    <a href="{{ route('cms.gallery.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    @if($item->file_path || $item->image_path)
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                <img 
                                    src="{{ asset('storage/' . ($item->file_path ?? $item->image_path)) }}" 
                                    alt="{{ $item->title }}" 
                                    class="img-thumbnail" 
                                    style="max-width: 300px;">
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cms.gallery.update', $item) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        id="title" 
                                        name="title" 
                                        class="form-control @error('title') is-invalid @enderror" 
                                        value="{{ old('title', $item->title) }}"
                                        required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea 
                                        id="description" 
                                        name="description" 
                                        class="form-control @error('description') is-invalid @enderror" 
                                        rows="4">{{ old('description', $item->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select 
                                        id="type" 
                                        name="type" 
                                        class="form-control @error('type') is-invalid @enderror" 
                                        required
                                        onchange="toggleMediaInput()">
                                        <option value="image" {{ old('type', $item->type ?? 'image') == 'image' ? 'selected' : '' }}>Image</option>
                                        <option value="video" {{ old('type', $item->type ?? 'image') == 'video' ? 'selected' : '' }}>Video</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3" id="image_input">
                                    <label for="file_path" class="form-label">Image</label>
                                    <input 
                                        type="file" 
                                        id="file_path" 
                                        name="file_path" 
                                        class="form-control-file @error('file_path') is-invalid @enderror" 
                                        accept="image/*">
                                    @error('file_path')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty to keep current image. Max 5MB</small>
                                </div>

                                <div class="mb-3 {{ ($item->type ?? 'image') == 'video' ? '' : 'd-none' }}" id="video_input">
                                    <label for="video_url" class="form-label">Video URL</label>
                                    <input 
                                        type="url" 
                                        id="video_url" 
                                        name="video_url" 
                                        class="form-control @error('video_url') is-invalid @enderror" 
                                        value="{{ old('video_url', $item->video_url ?? '') }}">
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="thumbnail" class="form-label">Thumbnail Image</label>
                                    @if($item->thumbnail_path)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $item->thumbnail_path) }}" alt="Thumbnail" class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                    @endif
                                    <input 
                                        type="file" 
                                        id="thumbnail" 
                                        name="thumbnail" 
                                        class="form-control-file @error('thumbnail') is-invalid @enderror" 
                                        accept="image/*">
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty to keep current thumbnail</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="gallery_category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select 
                                        id="gallery_category_id" 
                                        name="gallery_category_id" 
                                        class="form-control @error('gallery_category_id') is-invalid @enderror" 
                                        required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('gallery_category_id', $item->gallery_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gallery_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input 
                                        type="number" 
                                        id="sort_order" 
                                        name="sort_order" 
                                        class="form-control @error('sort_order') is-invalid @enderror" 
                                        value="{{ old('sort_order', $item->sort_order ?? 0) }}"
                                        min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input 
                                            type="checkbox" 
                                            id="is_featured" 
                                            name="is_featured" 
                                            class="form-check-input" 
                                            value="1"
                                            {{ old('is_featured', $item->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Featured Item
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input 
                                            type="checkbox" 
                                            id="is_active" 
                                            name="is_active" 
                                            class="form-check-input" 
                                            value="1"
                                            {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i> Update Item
                            </button>
                            <a href="{{ route('cms.gallery.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMediaInput() {
    const type = document.getElementById('type').value;
    const imageInput = document.getElementById('image_input');
    const videoInput = document.getElementById('video_input');
    
    if (type === 'image') {
        imageInput.classList.remove('d-none');
        videoInput.classList.add('d-none');
    } else {
        imageInput.classList.add('d-none');
        videoInput.classList.remove('d-none');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleMediaInput();
});
</script>
@endsection

