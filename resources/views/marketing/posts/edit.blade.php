<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Marketing Post
            </h2>
            <a href="{{ route('marketing.posts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                <i class="fa fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('marketing.posts.update', $post) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Type <span class="text-red-500">*</span>
                                    </label>
                                    <select id="type" name="type" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="social" {{ old('type', $post->type) == 'social' ? 'selected' : '' }}>Social Media</option>
                                        <option value="blog" {{ old('type', $post->type) == 'blog' ? 'selected' : '' }}>Blog</option>
                                        <option value="email" {{ old('type', $post->type) == 'email' ? 'selected' : '' }}>Email</option>
                                        <option value="sms" {{ old('type', $post->type) == 'sms' ? 'selected' : '' }}>SMS</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="platform" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Platform
                                    </label>
                                    <select id="platform" name="platform"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select Platform</option>
                                        <option value="facebook" {{ old('platform', $post->platform) == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                        <option value="instagram" {{ old('platform', $post->platform) == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                        <option value="twitter" {{ old('platform', $post->platform) == 'twitter' ? 'selected' : '' }}>Twitter/X</option>
                                        <option value="linkedin" {{ old('platform', $post->platform) == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                        <option value="tiktok" {{ old('platform', $post->platform) == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="campaign_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Campaign
                                    </label>
                                    <select id="campaign_id" name="campaign_id"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">No Campaign</option>
                                        @foreach($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}" {{ old('campaign_id', $post->campaign_id) == $campaign->id ? 'selected' : '' }}>
                                                {{ $campaign->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Schedule Post
                                    </label>
                                    <input type="datetime-local" id="scheduled_at" name="scheduled_at" 
                                        value="{{ old('scheduled_at', $post->scheduled_at ? $post->scheduled_at->format('Y-m-d\TH:i') : '') }}"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Status
                                    </label>
                                    <select id="status" name="status"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending" {{ old('status', $post->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $post->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="archived" {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="hashtags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Hashtags
                                    </label>
                                    <textarea id="hashtags" name="hashtags" rows="3"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">{{ old('hashtags', $post->hashtags) }}</textarea>
                                </div>

                                <div>
                                    <label for="cta_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Call-to-Action Text
                                    </label>
                                    <input type="text" id="cta_text" name="cta_text" value="{{ old('cta_text', $post->cta_text) }}"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="cta_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Call-to-Action URL
                                    </label>
                                    <input type="url" id="cta_url" name="cta_url" value="{{ old('cta_url', $post->cta_url) }}"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mt-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content" name="content" rows="10" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">{{ old('content', $post->content) }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-end space-x-4">
                            <a href="{{ route('marketing.posts.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                                <i class="fa fa-save mr-2"></i>Update Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

