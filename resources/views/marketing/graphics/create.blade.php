@extends('layouts.app')

@section('title', 'Upload Graphic')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
            <i class="fa-solid fa-cloud-arrow-up text-pink-600 mr-3"></i> Upload Graphic Asset
        </h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload or create a new graphic asset</p>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('marketing.graphics.store') }}" enctype="multipart/form-data" class="max-w-2xl">
        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Asset Details</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-pink-500 focus:border-pink-500"
                        placeholder="e.g., Health Awareness Banner">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type *</label>
                    <select name="type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-pink-500 focus:border-pink-500">
                        <option value="">Select type</option>
                        <option value="poster" {{ old('type') == 'poster' ? 'selected' : '' }}>Poster</option>
                        <option value="infographic" {{ old('type') == 'infographic' ? 'selected' : '' }}>Infographic</option>
                        <option value="social_media" {{ old('type') == 'social_media' ? 'selected' : '' }}>Social Media</option>
                        <option value="banner" {{ old('type') == 'banner' ? 'selected' : '' }}>Banner</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Brief description of the graphic">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image File *</label>
                    <input type="file" name="image" accept="image/*" required
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-pink-500 focus:border-pink-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-pink-100 file:text-pink-700 hover:file:bg-pink-200">
                    <p class="text-xs text-gray-500 mt-1">Max 10MB. Supports JPG, PNG, GIF, SVG.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Campaign</label>
                    <select name="campaign_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-pink-500 focus:border-pink-500">
                        <option value="">No campaign</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">AI Generation Prompt (optional)</label>
                    <input type="text" name="ai_prompt" value="{{ old('ai_prompt') }}"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Describe what you want to generate">
                    <p class="text-xs text-gray-500 mt-1">If filled, this asset will be marked as AI-generated</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white font-semibold rounded-lg shadow-md transition">
                <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Upload Asset
            </button>
            <a href="{{ route('marketing.graphics.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-300 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
