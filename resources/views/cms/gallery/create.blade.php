@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-plus mr-2"></i> Add Gallery Item</h4>
                    <a href="{{ route('cms.gallery.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cms.gallery.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        id="title" 
                                        name="title" 
                                        class="form-control @error('title') is-invalid @enderror" 
                                        value="{{ old('title') }}"
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
                                        rows="4">{{ old('description') }}</textarea>
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
                                        <option value="image" {{ old('type', 'image') == 'image' ? 'selected' : '' }}>Image</option>
                                        <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3" id="image_input">
                                    <label for="file_path" class="form-label">Image <span class="text-danger" id="image_required">*</span></label>
                                    <input 
                                        type="file" 
                                        id="file_path" 
                                        name="file_path" 
                                        class="form-control-file @error('file_path') is-invalid @enderror" 
                                        accept="image/*">
                                    @error('file_path')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Max 5MB. Supported formats: JPG, PNG, GIF</small>
                                </div>

                                <div class="mb-3 d-none" id="video_input">
                                    <label for="video_url" class="form-label">Video URL <span class="text-danger">*</span></label>
                                    <input 
                                        type="url" 
                                        id="video_url" 
                                        name="video_url" 
                                        class="form-control @error('video_url') is-invalid @enderror" 
                                        value="{{ old('video_url') }}"
                                        placeholder="https://www.youtube.com/watch?v=...">
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="thumbnail" class="form-label">Thumbnail Image</label>
                                    <input 
                                        type="file" 
                                        id="thumbnail" 
                                        name="thumbnail" 
                                        class="form-control-file @error('thumbnail') is-invalid @enderror" 
                                        accept="image/*">
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Max 2MB. Optional thumbnail for videos</small>
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
                                            <option value="{{ $category->id }}" {{ old('gallery_category_id') == $category->id ? 'selected' : '' }}>
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
                                        value="{{ old('sort_order', 0) }}"
                                        min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input 
                                            type="checkbox" 
                                            id="is_featured" 
                                            name="is_featured" 
                                            class="form-check-input" 
                                            value="1"
                                            {{ old('is_featured') ? 'checked' : '' }}>
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
                                            {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i> Add Item
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
    const imageRequired = document.getElementById('image_required');
    const filePathInput = document.getElementById('file_path');
    const videoUrlInput = document.getElementById('video_url');
    
    if (type === 'image') {
        imageInput.classList.remove('d-none');
        videoInput.classList.add('d-none');
        imageRequired.textContent = '*';
        filePathInput.required = true;
        videoUrlInput.required = false;
    } else {
        imageInput.classList.add('d-none');
        videoInput.classList.remove('d-none');
        imageRequired.textContent = '';
        filePathInput.required = false;
        videoUrlInput.required = true;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleMediaInput();
});
</script>
@endsection

