@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-plus mr-2"></i> Create New Blog Post</h4>
                    <a href="{{ route('cms.blog.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cms.blog.store') }}" enctype="multipart/form-data">
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
                                    <label for="slug" class="form-label">Slug</label>
                                    <input 
                                        type="text" 
                                        id="slug" 
                                        name="slug" 
                                        class="form-control @error('slug') is-invalid @enderror" 
                                        value="{{ old('slug') }}"
                                        placeholder="auto-generated">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty to auto-generate from title</small>
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea 
                                        id="content" 
                                        name="content" 
                                        class="form-control @error('content') is-invalid @enderror" 
                                        rows="15"
                                        required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="excerpt" class="form-label">Excerpt</label>
                                    <textarea 
                                        id="excerpt" 
                                        name="excerpt" 
                                        class="form-control @error('excerpt') is-invalid @enderror" 
                                        rows="3">{{ old('excerpt') }}</textarea>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Brief summary of the post</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="blog_category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select 
                                        id="blog_category_id" 
                                        name="blog_category_id" 
                                        class="form-control @error('blog_category_id') is-invalid @enderror" 
                                        required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('blog_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('blog_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select 
                                        id="status" 
                                        name="status" 
                                        class="form-control @error('status') is-invalid @enderror" 
                                        required>
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="published_at" class="form-label">Published Date</label>
                                    <input 
                                        type="datetime-local" 
                                        id="published_at" 
                                        name="published_at" 
                                        class="form-control @error('published_at') is-invalid @enderror" 
                                        value="{{ old('published_at') }}">
                                    @error('published_at')
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
                                            {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Featured Post
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="featured_image" class="form-label">Featured Image</label>
                                    <input 
                                        type="file" 
                                        id="featured_image" 
                                        name="featured_image" 
                                        class="form-control-file @error('featured_image') is-invalid @enderror" 
                                        accept="image/*">
                                    @error('featured_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Max 2MB</small>
                                </div>

                                <hr>

                                <h6>SEO Settings</h6>

                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input 
                                        type="text" 
                                        id="meta_title" 
                                        name="meta_title" 
                                        class="form-control" 
                                        value="{{ old('meta_title') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea 
                                        id="meta_description" 
                                        name="meta_description" 
                                        class="form-control" 
                                        rows="2">{{ old('meta_description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input 
                                        type="text" 
                                        id="meta_keywords" 
                                        name="meta_keywords" 
                                        class="form-control" 
                                        value="{{ old('meta_keywords') }}"
                                        placeholder="keyword1, keyword2">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i> Create Post
                            </button>
                            <a href="{{ route('cms.blog.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

