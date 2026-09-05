<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.icd10.index') }}" class="hover:text-blue-600">ICD-10 Codes</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $code->code }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-book-medical text-blue-600 mr-3"></i>{{ $code->code }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.icd10.edit', $code) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.icd10.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">ICD-10 Code Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Code:</span> <span class="font-mono font-semibold text-blue-600 dark:text-blue-400">{{ $code->code }}</span></div>
                    <div><span class="text-gray-500">Category:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $code->category ?? 'N/A' }}</span></div>
                    <div class="md:col-span-2"><span class="text-gray-500">Description:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $code->description }}</span></div>
                    <div><span class="text-gray-500">Type:</span>
                        @if($code->is_chapter_heading)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Chapter Heading</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Code</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
