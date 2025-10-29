<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Create Marketing Post
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
                    <form method="POST" action="{{ route('marketing.posts.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Type <span class="text-red-500">*</span>
                                    </label>
                                    <select id="type" name="type" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="social" {{ old('type') == 'social' ? 'selected' : '' }}>Social Media</option>
                                        <option value="blog" {{ old('type') == 'blog' ? 'selected' : '' }}>Blog</option>
                                        <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>Email</option>
                                        <option value="sms" {{ old('type') == 'sms' ? 'selected' : '' }}>SMS</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="platform" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Platform
                                    </label>
                                    <select id="platform" name="platform"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select Platform</option>
                                        <option value="facebook" {{ old('platform') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                        <option value="instagram" {{ old('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                        <option value="twitter" {{ old('platform') == 'twitter' ? 'selected' : '' }}>Twitter/X</option>
                                        <option value="linkedin" {{ old('platform') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                        <option value="tiktok" {{ old('platform') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="campaign_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Campaign (Optional)
                                    </label>
                                    <select id="campaign_id" name="campaign_id"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">No Campaign</option>
                                        @foreach($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                                {{ $campaign->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Schedule Post (Optional)
                                    </label>
                                    <input type="datetime-local" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}"
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
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="hashtags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Hashtags
                                    </label>
                                    <textarea id="hashtags" name="hashtags" rows="3"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="#health #wellness #hospital">{{ old('hashtags') }}</textarea>
                                </div>

                                <div>
                                    <label for="cta_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Call-to-Action Text
                                    </label>
                                    <input type="text" id="cta_text" name="cta_text" value="{{ old('cta_text') }}"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Learn More">
                                </div>

                                <div>
                                    <label for="cta_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Call-to-Action URL
                                    </label>
                                    <input type="url" id="cta_url" name="cta_url" value="{{ old('cta_url') }}"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="https://example.com">
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mt-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content" name="content" rows="10" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- AI Generation Helper -->
                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-200 mb-2">
                                <i class="fa fa-magic mr-2"></i>Use AI to generate content, hashtags, or CTAs
                            </p>
                            <div class="flex space-x-2">
                                <button type="button" id="btn-generate-content" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                                    Generate Content
                                </button>
                                <button type="button" id="btn-generate-hashtags" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 text-sm">
                                    Generate Hashtags
                                </button>
                                <button type="button" id="btn-generate-cta" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-sm">
                                    Generate CTA
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-end space-x-4">
                            <a href="{{ route('marketing.posts.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                                <i class="fa fa-save mr-2"></i>Create Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate Content Button
            document.getElementById('btn-generate-content').addEventListener('click', async function() {
                const prompt = prompt('Enter a topic or prompt for content generation:');
                if (!prompt) return;

                const button = this;
                const originalText = button.textContent;
                button.disabled = true;
                button.textContent = 'Generating...';

                try {
                    const response = await fetch('{{ route("marketing.ai.generate-content") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ prompt, type: 'post' })
                    });

                    const data = await response.json();
                    if (data.success && data.content) {
                        document.getElementById('content').value = data.content;
                        alert('Content generated successfully!');
                    } else {
                        alert('Failed to generate content. Please check your API key configuration.');
                    }
                } catch (error) {
                    alert('Error generating content: ' + error.message);
                } finally {
                    button.disabled = false;
                    button.textContent = originalText;
                }
            });

            // Generate Hashtags Button
            document.getElementById('btn-generate-hashtags').addEventListener('click', async function() {
                const content = document.getElementById('content').value;
                if (!content) {
                    alert('Please enter some content first');
                    return;
                }

                const platform = document.getElementById('platform').value || 'instagram';
                const button = this;
                const originalText = button.textContent;
                button.disabled = true;
                button.textContent = 'Generating...';

                try {
                    const response = await fetch('{{ route("marketing.ai.generate-hashtags") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ content, platform })
                    });

                    const data = await response.json();
                    if (data.success && data.hashtags) {
                        document.getElementById('hashtags').value = data.hashtags.join(' ');
                        alert('Hashtags generated successfully!');
                    } else {
                        alert('Failed to generate hashtags. Please check your API key configuration.');
                    }
                } catch (error) {
                    alert('Error generating hashtags: ' + error.message);
                } finally {
                    button.disabled = false;
                    button.textContent = originalText;
                }
            });

            // Generate CTA Button
            document.getElementById('btn-generate-cta').addEventListener('click', async function() {
                const content = document.getElementById('content').value;
                if (!content) {
                    alert('Please enter some content first');
                    return;
                }

                const button = this;
                const originalText = button.textContent;
                button.disabled = true;
                button.textContent = 'Generating...';

                try {
                    const response = await fetch('{{ route("marketing.ai.generate-cta") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ context: content })
                    });

                    const data = await response.json();
                    if (data.success && data.cta) {
                        document.getElementById('cta_text').value = data.cta;
                        alert('CTA generated successfully!');
                    } else {
                        alert('Failed to generate CTA. Please check your API key configuration.');
                    }
                } catch (error) {
                    alert('Error generating CTA: ' + error.message);
                } finally {
                    button.disabled = false;
                    button.textContent = originalText;
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

