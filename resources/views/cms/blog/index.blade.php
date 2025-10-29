@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-blog mr-2"></i> Blog Posts Management</h4>
                    <div>
                        <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary mr-2">
                            <i class="fa fa-arrow-left mr-1"></i> Back
                        </a>
                        <a href="{{ route('cms.blog.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus mr-1"></i> New Post
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
                    <form method="GET" action="{{ route('cms.blog.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input 
                                    type="text" 
                                    name="search" 
                                    class="form-control" 
                                    placeholder="Search posts..." 
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
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
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-search mr-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Posts Table -->
                    @if($posts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Views</th>
                                        <th>Published At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                        <tr>
                                            <td>
                                                <strong>{{ $post->title }}</strong>
                                                @if($post->excerpt)
                                                    <br><small class="text-muted">{{ Str::limit($post->excerpt, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($post->category)
                                                    <span class="badge" style="background-color: {{ $post->category->color ?? '#6366f1' }}">
                                                        {{ $post->category->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Uncategorized</span>
                                                @endif
                                            </td>
                                            <td>{{ $post->author->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($post->status == 'published')
                                                    <span class="badge badge-success">Published</span>
                                                @elseif($post->status == 'draft')
                                                    <span class="badge badge-warning">Draft</span>
                                                @else
                                                    <span class="badge badge-secondary">Archived</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($post->is_featured)
                                                    <i class="fa fa-star text-warning"></i>
                                                @else
                                                    <i class="fa fa-star text-muted"></i>
                                                @endif
                                            </td>
                                            <td>{{ $post->views_count ?? 0 }}</td>
                                            <td>
                                                {{ $post->published_at ? $post->published_at->format('Y-m-d') : 'Not published' }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('cms.blog.edit', $post) }}" class="btn btn-sm btn-info">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('cms.blog.destroy', $post) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle mr-2"></i> No blog posts found. 
                            <a href="{{ route('cms.blog.create') }}">Create your first post</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

